<?php $siteyolu = 'http://www.linuxhaber.com'; ?>
/* -------------------------------------*/
/* eyceks v1.3.1  						*/
/* eburhan [at] gmail [nokta] com 		*/
/* ------------------------------------ */

function AJAX() 
{
	var ajax = false;
	// Internet Explorer (5.0+)
	try 
	{
		ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch (e) 
	{
		try 
		{
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e) 
		{
			ajax = false;
		}
	}

	// Mozilla veya Safari
	if ( !ajax && typeof XMLHttpRequest != 'undefined' ) 
	{
		try
		{
			ajax = new XMLHttpRequest();
		}
		catch(e) 
		{ 
			ajax = false;
		}
	}

	// Diger (IceBrowser)
	if ( !ajax && window.createRequest ) 
	{
		try
		{
			ajax = window.createRequest();
		}
		catch(e) 
		{ 
			ajax = false;
		}
	}
	return ajax;
}


// POST işlemleri
function JXP(yukleniyor, yer, dosya, sc) 
{
	ajax = new AJAX();

	if ( ajax ) 
	{
		ajax.onreadystatechange = function ()
		{};
		ajax.abort()
	}

	ajax.onreadystatechange = function () 
	{
		Loading(yukleniyor, yer) 
	}

	ajax.open('POST', dosya, true)
	ajax.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT")
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8')
	ajax.setRequestHeader("Content-length", sc.length)
	ajax.setRequestHeader("Connection", "close")
	ajax.send(sc) 
}


// GET işlemleri
function JXG(yukleniyor, yer, dosya, sc) 
{
	ajax = new AJAX();

	if ( ajax ) 
	{
		ajax.onreadystatechange = function ()
		{};
		ajax.abort();
	}

	// son hazırlık
	if(sc) 
	{
		dosya = dosya +'?'+ sc;
	}

	ajax.onreadystatechange = function () 
	{
		Loading(yukleniyor, yer); 
	}

	ajax.open('GET', dosya, true);
	ajax.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT")
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8')
	ajax.setRequestHeader("Content-length", sc.length)
	ajax.setRequestHeader("Connection", "close")
	ajax.send(null);	
}


// Yükleme işlemleri
function Loading(yukleniyor, yer) 
{
	if( yukleniyor == 1 && yer != 'no_id' ) 
	{
		if( ajax.readyState == 1 || ajax.readyState == 2 || ajax.readyState == 3 ) 
		{
			var ekleniyor = '<img src="<?php echo $siteyolu?>/_lib_js/loading.gif">'
			document.getElementById(yer).innerHTML = ekleniyor;
		}
	}

	if( ajax.readyState == 4 && yer != 'no_id' ) 
	{
		if (ajax.status == 200) 
		{
			document.getElementById(yer).innerHTML = ajax.responseText;
		} 
		else 
		{
			document.getElementById(yer).innerHTML = '<strong>HATA:</strong> ' + ajax.statusText;
		}
		function AJAX()
		{};
	}
}


// Özel karakterleri zararsız hale dönüştür
// ( Fix Character )
function fc_(text) 
{
	var temp;
	temp = encodeURIComponent(text);
	return temp;
}

/* ---------------------------*/
/* eyceks v1.3.1 modifiyeleri */
/* ---------------------------*/

function GetXHR()
{
	// code for Firefox, Opera, IE7, etc.
	if (window.XMLHttpRequest)
	{
		xhr = new XMLHttpRequest();
	}
	// code for IE6, IE5
	else if (window.ActiveXObject)
	{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (xhr == null)
	{
		alert("Your browser does not support XMLHTTP.");
		return null;
	}
	return xhr;
}


function MakeRequest(url, yer)
{
	var xmlReq = GetXHR();
	var divId = yer;
	var urlStr = url;

	xmlReq.onreadystatechange = function() 
	{
		//sepete ekleniyor linkini gizliyoruz
		if( xmlReq.readyState == 1 || xmlReq.readyState == 2 || xmlReq.readyState == 3 ) 
		{
			var ekleniyor = '<img src="<?php echo $siteyolu?>/_lib_js/loading.gif">'
			if (document.getElementById(divId))
			{
				document.getElementById(divId).innerHTML = ekleniyor;
			}			
		}

		// 4 = loaded
		if (xmlReq.readyState == 4)
		{
			// 200 = OK
			if (xmlReq.status == 200)
			{
				if (document.getElementById(divId))
				{
					document.getElementById(divId).innerHTML = xmlReq.responseText;
				}
			}
			else
			{
				alert("Problem retrieving data:" + xmlReq.statusText);
			}
		}
	};
	xmlReq.open("POST",urlStr ,true);
	xmlReq.send(null);
}

function MakeSecretRequest(url, yer)
{
	var xmlReq = GetXHR();
	var divId = yer;
	var urlStr = url;
	xmlReq.onreadystatechange = function() 
	{
		// 4 = loaded
		if (xmlReq.readyState == 4)
		{
			// 200 = OK
			if (xmlReq.status == 200)
			{
				if (document.getElementById(divId))
				{
					document.getElementById(divId).innerHTML = xmlReq.responseText;
				}
			}
			else
			{
				alert("Problem retrieving data:" + xmlReq.statusText);
			}
		}
	};
	xmlReq.open("POST",urlStr ,true);
	xmlReq.send(null);
}

<?php $siteyolu = ''; ?>
