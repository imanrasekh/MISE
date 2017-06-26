<?php
class FitnessFunction {
	public $dimension;	// int
	protected $minVal;	// double
	protected $maxVal;	// double
	public $minBounds;	// double[]
	public $maxBounds;	// double[]
	public $nbEvals;	// int
	private function __init() { // default class members
		$this->dimension = 20;
		$this->minVal = -doubleval(2.048);
		$this->maxVal = doubleval(2.048);
		$this->nbEvals = 0;
	}
	public static function constructor__ () 
	{
		$me = new self();
		$me->__init();
		$me->minBounds = array();
		$me->maxBounds = array();
		for ($i = 0; ($i < $me->dimension); ++$i) 
		{
			$me->minBounds[$i] = $me->minVal;
			$me->maxBounds[$i] = $me->maxVal;
		}
		return $me;
	}
	public function getFitnessValue ($individual) // [double[] individual]
	{
		$fitness = 0;
		for ($i = 0; ($i < count($individual) /*from: individual.length*/); ++$i) 
		{
			$fitness = ($fitness + pow$individual[$i], 2);
		}
		++$this->nbEvals;
		return $fitness;
	}
}
?>
