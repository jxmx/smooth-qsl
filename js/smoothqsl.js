/*
Copyright 2017-2026 Jason D. McCormick
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

	return true;
}

