

// call PROFILE.show() to show the profileViewer

// added this to keep the script independent from pathes 
// http://www.experts-exchange.com/Programming/Languages/Scripting/JavaScript/Q_24662495.html
function basename (path, suffix) {
    // Returns the filename component of the path  
    // 
    // version: 909.322
    // discuss at: http://phpjs.org/functions/basename
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Ash Searle (http://hexmen.com/blog/)
    // +   improved by: Lincoln Ramsay
    // +   improved by: djmix
    // *     example 1: basename('/www/site/home.htm', '.htm');
    // *     returns 1: 'home'
    var b = path.replace(/^.*[\/\\]/g, '');
    
    if (typeof(suffix) == 'string' && b.substr(b.length-suffix.length) == suffix) {
        b = b.substr(0, b.length-suffix.length);
    }
    
    return b;
}


function getscriptpath(scriptname){
                // Check document for our script
                scriptobjects = document.getElementsByTagName('script');
                for (i=0; i<scriptobjects.length; i++) {
                        if(basename(scriptobjects[i].src)==scriptname){
                                // we found our script.. lets get the path
                                return scriptobjects[i].src.substring(0, scriptobjects[i].src.lastIndexOf('/'));
                        };
                }
                return "";
}

var PROFILE = {

  init : function(bool) {
  
  	// define what objects, constructors and functions you want to profile
  	// documentation here: http://developer.yahoo.com/yui/profiler/
  	
  	YAHOO.tool.Profiler.registerObject("jQuery", jQuery, true);
  	
  	// the following would profile all methods within constructor's prototype
    // YAHOO.tool.Profiler.registerConstructor("Person");
  	
    // the following would profile the global function sayHi
    // YAHOO.tool.Profiler.registerFunction("sayHi", window); 
    
    // if true is passed into init(), F9 will bring up the profiler
    if (bool){
      $(document).keyup(function(e){
        if (e.keyCode === 120){ 
          PROFILE.show(); 
          $(document).unbind('keyup',arguments.callee); 
        }
      })
    }
  },
  
  //When the showProfile button is clicked, use YUI Loader to get all required
  //dependencies and then show the profile:
  show : function() {
  
          
          var currentpath = getscriptpath( 'config.js?v=1' );
          
          var s = document.createElement('link');
          s.setAttribute('rel','stylesheet');      
          s.setAttribute('type','text/css');
          s.setAttribute('href',currentpath+'/yahoo-profiling.css');
          document.body.appendChild(s);
          
	        YAHOO.util.Dom.addClass(document.body, 'yui-skin-sam');

      		//instantiate ProfilerViewer with desired options:
      		var pv = new YAHOO.widget.ProfilerViewer("", {
      			visible: true, //expand the viewer mmediately after instantiation
      			showChart: true,
      		  //	base:"../../build/",
      		  swfUrl: currentpath+"/charts.swf"
      		});
  	
  }

};

// check some global debug variable to see if we should be profiling..
if (true) { PROFILE.init(true) }

// start profiling
PROFILE.show();

