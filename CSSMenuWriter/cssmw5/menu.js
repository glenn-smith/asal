var cssmw5 = {
  intializeMenu: function(id) {
   	if(navigator.appName == 'Microsoft Internet Explorer' && cssmw5.ieVersion() < 7) {
	  var lis = document.getElementById(id).getElementsByTagName('li');
      for(var i=0; i<lis.length; i++) {
	    var li = lis[i];
	    li.className = 'link';
		 var uls = li.getElementsByTagName('ul');
		 for(var u=0; u<uls.length; u++){
         uls[u].style.display = 'none';
		 }
	    var span = li.getElementsByTagName('span')[0];
	    if(span) span.className = 'link';
	    var a = (span) ? span.getElementsByTagName('a')[0] : false;
	    if(a) a.className = 'link';
	    li.onmouseover = function(e) {
	      this.className = 'hover';
	      var ul = this.getElementsByTagName('ul')[0];
			if(ul) ul.style.display = 'block';
	      var span = this.getElementsByTagName('span')[0];
	      if(span) span.className = 'hover';
	      var a = (span) ? span.getElementsByTagName('a')[0] : false;
  	      if(a) a.className = 'hover';
	    }
	    li.onmouseout = function(e) {
	      this.className = 'link';
	      var ul = this.getElementsByTagName('ul')[0];
			if(ul) ul.style.display = 'none';
	      var span = this.getElementsByTagName('span')[0];
	      if(span) span.className = 'link';
	      var a = (span) ? span.getElementsByTagName('a')[0] : false;
  	      if(a) a.className = 'link';			
	    }
 	  }
    }
  },
  
  ieVersion: function() {
    var ua = navigator.userAgent.toLowerCase();
    var offset = ua.indexOf("msie ");  
	return (offset == -1) ? 0 : parseFloat(ua.substring(offset + 5, ua.indexOf(";", offset)));
  }  
}