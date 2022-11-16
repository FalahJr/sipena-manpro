$(function () {
		'use strict';
		if($('#splitterExample1').length) {
			$('#splitterExample1').multiselectsplitter();
		}

		if($('#splitterExample2').length) {
			$('#splitterExample2').multiselectsplitter({
	    		selectSize: 7,
	        clearOnFirstChange: true,
	    		groupCounter: true
	    });
		}

		if($('#splitterExample3').length) {
			$('#splitterExample3').multiselectsplitter({
	    		groupCounter: true,
	        maximumSelected: 2
	    });
		}


		if($('#splitterExample4').length) {
			$('#splitterExample4').multiselectsplitter({
	    		groupCounter: true,
	        maximumSelected: 3,
	        onlySameGroup: true
	    });
		}


		if($('#splitterExample5').length) {
			$('#splitterExample5').multiselectsplitter({
	        size: 6,
	    		groupCounter: true,
	        maximumSelected: 2,
	        maximumAlert: function(maximumSelected) {
	        		alert('You choose '+( maximumSelected + 1 )+ ' options. Are you crazy ?');
	        },
	        afterInitialize: function($firstSelect, $secondSelect) {
	        		$("body").append('This text was edded after initialization of example5');
	        },
	        createFirstSelect: function (label, $originalSelect) {
	        		return '<option class="text-success">prefix - ' + label + '</option>';
	        },
	        createSecondSelect: function (label, $firstSelect) {
	        		return '<option class="text-danger"> ??? </option>';
	        }
	    });
		}

});
