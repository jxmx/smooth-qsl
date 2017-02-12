/*
Copyright 2017 Jason D. McCormick
Licensed under the Apache 2.0 License - http://www.apache.org/licenses/LICENSE-2.0
*/
function validateQsoFetchForm(){
	var qsoform = document.getElementById("qsofetch");
	var qsos = qsoform.elements['qq[]'];
	var maxqso = qsoform.elements['maxqso'].value;
	var count = 0;

	if( qsos.nodeName === undefined ){
		for( var qsoi = 0; qsoi < qsos.length; qsoi++){
			if(qsos[qsoi].checked == true){
				count++;
			}
		}
	} else {
		count = 1;
	}

	if(count == 0){
		alert("Please choose at least one QSO to print.");
		return false;
	}
	if(count > maxqso){
		alert("Please choose no more than " + maxqso + " QSOs")
		return false;
	}

	if(count > 1){
		qsoform.action = "qslprintmulti.php";
	}
	
	alert("Your QSL card/certificate is being generated. Click OK and you will be prompted to print or save a PDF file. Once you have your QSL, please close the browser window or tab.");

	return true;
}

