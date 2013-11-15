<?php
?>

<div id="searchDiv" class="searchDivCls">
<p class="searchP">Please type the word to search and a proper language</p>
</br>
<form id="searchFrm" action="view.php" method="get">
<table class="searchTabCls" border="1">
<tr>
    <td>
    <fieldset>
        <legend>Type the word or the synsetid</legend>
        <input type="text" id="searchTxt" class="searchTxtCls" name="elem" value="">
         Word<input type="radio" name="typeofsearch" value="0" checked id="sbyw"/>&nbsp;&nbsp;
         Synsetid<input type="radio" name="typeofsearch" value="1" id="sbys"/>
         
        </fieldset>
    </td>
    <td>
        <fieldset>
        <legend>Select the input language</legend>
        English <input type="radio" name="lang" value="eng" checked id="leng"onchange="disableAccordingTgtlang('leng');"/></br>
        Greek  <input type="radio" name="lang" value="grc" id="lgrc" onchange="disableAccordingTgtlang('lgrc');"/></br>
        Italian <input type="radio" name="lang" value="ita" id="lita" onchange="disableAccordingTgtlang('lita');"/></br>
        Latin <input type="radio" name="lang" value="lat" id="llat" onchange="disableAccordingTgtlang('llat');"/></br>
        Arabic <input type="radio" name="lang" value="ara" id="lara" onchange="disableAccordingTgtlang('lara');"/></br>
    </fieldset>
    </td>
    <td>
        <fieldset>
        <legend>Select the target languages</legend>
        Greek <input type="checkBox" name="tlang[]" value="grc" checked id="tlgrc"/></br>
        Latin <input type="checkBox" name="tlang[]" value="lat"  id="tllat"/></br>
        Italian <input type="checkBox" name="tlang[]" value="ita"  id="tlita"/></br>
        Arabic <input type="checkBox" name="tlang[]" value="ara" id="tlara" /></br>
        English <input type="checkBox" name="tlang[]" value="eng"  id="tleng" disabled/></br>
    </fieldset>
    </td>
 </tr>
 <tr>
    <td colspan="3"  style="align: center;">
        <input type="button" value="Search" onclick="formSubmit();">
         <input type="button" value="Reset" onclick="resetForm();">
    </td>
  </tr> 
  </table>
</form>
</div>
<script language="Javascript">
function formSubmit(){
    /*checkdate fields and decode*/
    var value=document.getElementById("searchTxt").value;
    var value1=value;
    
    if (value1.trim()==""){
        alert ("There is no item to search for");
        document.getElementById("searchTxt").value=value;
       
       
        } else {
             document.getElementById("searchFrm").submit();
            }
}

function resetForm(){
    /*checkdate fields and decode*/
    document.getElementById("searchTxt").value="";
    document.getElementById("sbyw").checked=true;
    document.getElementById("tlgrc").checked=true;
    document.getElementById("tlita").checked=false;
    document.getElementById("tllat").checked=false;
    document.getElementById("tlara").checked=false;
    document.getElementById("tleng").checked=false;
    document.getElementById("leng").checked=true;
}

function resetForm(){
    /*checkdate fields and decode*/
    document.getElementById("searchTxt").value="";
    document.getElementById("sbyw").checked=true;
    document.getElementById("tlgrc").checked=true;
    document.getElementById("tlita").checked=false;
    document.getElementById("tllat").checked=false;
    document.getElementById("tlara").checked=false;
    document.getElementById("tleng").checked=false;
    document.getElementById("leng").checked=true;
}

function disableAccordingTgtlang(id){
    var tgt=["leng","lita","lgrc","llat","lara"]
    document.getElementById(id).checked=true;
    for (i=0; i<tgt.length ; i++){
        var current=tgt[i];
        var tcurrent="t"+current;
         document.getElementById(tcurrent).disabled=false;
         
        if (current==id){
            document.getElementById(tcurrent).disabled=true;
            document.getElementById(tcurrent).checked=false;
            }
    }
}
function utf8_encode (argString) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: sowberry
  // +    tweaked by: Jack
  // +   bugfixed by: Onno Marsman
  // +   improved by: Yves Sucaet
  // +   bugfixed by: Onno Marsman
  // +   bugfixed by: Ulrich
  // +   bugfixed by: Rafal Kukawski
  // +   improved by: kirilloid
  // +   bugfixed by: kirilloid
  // *     example 1: utf8_encode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'

  if (argString === null || typeof argString === "undefined") {
    return "";
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
         (c1 >> 6)        | 192,
        ( c1        & 63) | 128
      );
    } else if (c1 & 0xF800 != 0xD800) {
      enc = String.fromCharCode(
         (c1 >> 12)       | 224,
        ((c1 >> 6)  & 63) | 128,
        ( c1        & 63) | 128
      );
    } else { // surrogate pairs
      if (c1 & 0xFC00 != 0xD800) { throw new RangeError("Unmatched trail surrogate at " + n); }
      var c2 = string.charCodeAt(++n);
      if (c2 & 0xFC00 != 0xDC00) { throw new RangeError("Unmatched lead surrogate at " + (n-1)); }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
         (c1 >> 18)       | 240,
        ((c1 >> 12) & 63) | 128,
        ((c1 >> 6)  & 63) | 128,
        ( c1        & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}

function utf8_decode (str_data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Norman "zEh" Fuchs
  // +   bugfixed by: hitwork
  // +   bugfixed by: Onno Marsman
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: kirilloid
  // *     example 1: utf8_decode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'

  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0,
    c4 = 0;

  str_data += '';

  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 <= 191) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 <= 223) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else if (c1 <= 239) {
      // http://en.wikipedia.org/wiki/UTF-8#Codepage_layout
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      c4 = str_data.charCodeAt(i + 3);
      c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
      c1 -= 0x10000;
      tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1>>10) & 0x3FF));
      tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
      i += 4;
    }
  }

  return tmp_arr.join('');
}


</script>