var webpage = require('webpage'),
    system = require('system'),
    Logger = require('./logger').default,
    initTime = Date.now();


/* === Console arguments parsing start === */
var url = system.args[1] || null;
var limit = system.args[2] || 0;
var isVerbose = system.args[3] === 'true';
/* === Console arguments parsing end === */


var logger = new Logger(isVerbose);

logger.log('Parser script is initialized');

(function (url, webpage, limit, logger) {

 function createWebPage() {
     const page = webpage.create();
     /**
      * Log messages from website page to console for debugging
      * @param msg
      */
     page.onAlert = function(msg) {
         logger.log(msg, 'debug');
     };
     return page;
 }

/**
 * Formats script response
 *
 * @param {object} response
 * @returns {string} - JSON-formatted response
 */
 function formatResponse(response) {
     return JSON.stringify({
         url: url,
         limit: limit,
         response: response,
         time: Date.now() - initTime
     }, null, 2);
 }

/**
 * Parse article list from newsfeed
 */
 function getArticlesList(page) {
     return page.evaluate(function(limit) {
         function getNewsContainer() {
             return document.getElementsByClassName('news_all')[0];
         }
         function getArticleData(articleBlock) {
             var link = articleBlock.getElementsByTagName('a')[0];
             var time = articleBlock.getElementsByClassName('article__time')[0];
             var subtitle = articleBlock.getElementsByClassName('article__subtitle')[0];
             var bold =  articleBlock.getElementsByClassName('article__bold')[0];
             
            
             if(time !== null){
             
             return {
                 title: link.innerHTML,
                 url: link.href,
                 time: time.innerHTML,
                 isBold: articleBlock.classList.contains('article_bold'),
                 subtitle: subtitle.innerHTML,
                 /** Full property is needed to write details article parsing info later */
                 full: {}
             };
         }
     }

         /**
          * @TODO: slice elements from article if limit is > 0
          **/
         var articles = getNewsContainer().getElementsByClassName('article');
         
         
         articles = Array.prototype.slice.call(articles, 0, limit)
         
         
         
         
         var result = [];

         alert('Articles fetched');


         for (var index in articles) if (articles.hasOwnProperty(index)) {
             try {
                 var article = getArticleData(articles[index]);
                 result.push(article);
             } catch (e) {
                 alert('Unrecognized article format:' + e.toString());
             }
         }
         return result;
     }, limit);
 }

/**
 * Parse article content from https://www.pravda.com.ua/
 */
 function pravdaArticleParser(page) {
     return page.evaluate(function () {
         var title = document.getElementsByClassName('post_news__title')[0];
         var content = document.getElementsByClassName('post_news__text')[0];
         var time = document.getElementsByClassName('post_news__date')[0];
         var tags = document.getElementsByTagName('span')[0];

       
         
         /** TODO: add missed params parsing **/
         return {
             title: title.innerHTML,
             content: content.innerHTML,
             tags: tags.innerHTML,
             time: time.innerHTML,
            
         };
     });
 }

/**
 * Parse article content from https://www.epravda.com.ua/
 */
 function ePravdaArticleParser(page) {
    return page.evaluate(function () {
        var title = document.getElementsByClassName('post__title')[0];
        var content = document.getElementsByClassName('post__text')[0];
         var time = document.getElementsByClassName('post_time')[0];
         var tags = document.getElementsByTagName('span')[0];
        /** TODO: add missed params parsing **/
        return {
            title: title.textContent,
            content: content.innerHTML,
            tags: tags.innerHTML,
            time: time.innerHTML,
           
        };
    });
 }
 
 
 
 
  function evroPravdaArticleParser(page){
      
        return page.evaluate(function () {
        var title = document.getElementsByClassName('title')[0];
        var content = document.getElementsByClassName('text')[0];
        var time = document.getElementsByClassName('d_t2')[0];
       
        /** TODO: add missed params parsing **/
        return {
            title: title.textContent,
            content: content.innerHTML,
            time: time.innerHTML,
           
        };
    });
  }

/**
 * Defines which article parser function need to be used for specified
 * article url
 *
 * @param {string} url
 * @returns {function} parser, or null in case if article host is unsupported
 */
 function getArticleParser(url) {
    if (url.search(/^https:\/\/www.epravda.com.ua\/*/) === 0) {
        return ePravdaArticleParser;
    } else if (url.search(/^https:\/\/www.pravda.com.ua\/*/) === 0)  {
        return pravdaArticleParser;
    }
    return null;
 }

/**
 * Recursively open detailed view page, waiting for page initialization,
 * fetches article additional data, and then calls itself with incremented article index,
 * when all articles is already parsed it prints result and kills phantom script
 *
 * @param {array} articlesList - list of all articles that needs to be parsed
 * @param {number} i - current article index
 */
 function runArticlesPagesParser(articlesList, i) {
    !i && (i = 0);

    if (!articlesList.hasOwnProperty(i)) { // in case if we reach to end of list, we need to send response
        console.log(formatResponse(articlesList));
        return phantom.exit();
    }

    var currentArticle = articlesList[i];
    var articlePage = createWebPage();
    articlePage.open(currentArticle.url, function () {
         setTimeout(function() {
             logger.log('Article page is opened: ' + currentArticle.url);
             try {
                 /**
                  * Our newsfeed aggregates articles from different sources, so
                  * we need support different articles parsers for each of source
                  */
                 var articleParserFn = getArticleParser(currentArticle.url);
                 if (!articleParserFn) {
                     logger.log(
                         'Cant resolve article parser for article with unsupported domain: "' +  currentArticle.url + "'"
                     );
                     // Remove element from list if it can't be parsed
                     articlesList.splice(i, 1);
                     articlePage.close();
                     runArticlesPagesParser(articlesList, i);
                 } else {
                     currentArticle.full = articleParserFn(articlePage);
                     logger.log('Article is parsed successfully: ' + currentArticle.url);
                     articlePage.close();
                     runArticlesPagesParser(articlesList, i + 1);
                 }
             } catch (e) {
                 logger.log('Parsing stopped because of error: ' + e.message, 'error');
                 phantom.exit();
             }
         }, 100);
     });
 }

/**
 * Main function opens news list page, fetches articles and then runs article detailed page parses
 */
 function run() {
    var newsfeedPage = createWebPage();
    newsfeedPage.open(url, function () {
        logger.log('News list page opened');
        setTimeout(function() {
            logger.log('News list page initialized');
            try {
                var articlesList = getArticlesList(newsfeedPage);
                // Uncomment it, to test one page parsing
                // articlesList = [
                //     {
                //         url: 'https://www.pravda.com.ua/rus/news/2018/03/9/7174133/',
                //         full: {}
                //     }
                // ];
                newsfeedPage.close();
                runArticlesPagesParser(articlesList);
            } catch(e) {
                logger.log('Parsing stopped because of error: ' + e.message, 'error');
                phantom.exit();
            }
        }, 100);
     });
 }

 run();
})(url, webpage, limit, logger);