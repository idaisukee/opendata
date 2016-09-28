<?php
function ma()
{
	try {
		mc();
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
}

function mb()
{
	try {
		throw new Exception('err1');
	} catch (Exception $e) {
		//		throw new Exception('err2');
		throw new Exception('err3', $e->getCode(), $e);
	}
	
}


function mc()
{
	throw new Exception('err4');
}
ma();