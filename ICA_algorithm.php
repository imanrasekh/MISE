<?php

class ICAlgorithm {
	protected $CountriesNO;                 //Number of countries;
	protected $initialImperialistsNO;       //Number of Initial Imperialists
	protected $allColoniesNO;               //Number of all Colonies
	protected $decadesNO;                   //number of decades
	protected $rateOfRevolutions;
	protected $assimilationCoefficient;
	protected $assimilationAngleCoefficient;
	protected $zeta;
	protected $dampRatio;
	protected $stopIfJustOneEmpire;
	protected $thresholdOfUniting;
    protected $seed;
	protected $r;
	protected $problemDimension;
	protected $minBounds;
	protected $maxBounds;
	protected $empiresList;
	protected $initialCountries;
	protected $initialCosts;
	protected $bestDecadePosition;
    protected $minimumCost;
	protected $meanCost;
	protected $searchSpaceSize;
	protected $library;
    protected $fitnessFunc;
	private function __init() {  
		$this->numOfCountries = 50;
		$this->numOfInitialImperialists = 6;
		$this->numOfAllColonies = ($this->numOfCountries - $this->numOfInitialImperialists);
		$this->numOfDecades = 10000;
		$this->revolutionRate = doubleval(0.1);
		$this->assimilationCoefficient = 2;
		$this->assimilationAngleCoefficient = doubleval(.5);
		$this->zeta = doubleval(0.02);
		$this->dampRatio = doubleval(0.99);
		$this->stopIfJustOneEmpire =  FALSE ;
		$this->unitingThreshold = doubleval(0.02);
		$this->seed = round(microtime(true) * 1000);
		$this->r = new Random($this->seed);
		$this->empiresList = array();
		$this->minimumCost = array();
		$this->meanCost = array();
		$this->utils = new ICAUtils();
	}
	public static function constructor__FitnessFunction ($fitnessFunc)
	{
		$me = new self();
		$me->__init();
		$me->fitnessFunc = $fitnessFunc;
		$me->problemDimension = $fitnessFunc->dimension;
		$me->minBounds = $fitnessFunc->minBounds;
		$me->maxBounds = $fitnessFunc->maxBounds;
		return $me;
	}
	protected function ICA_mainFunction ($maxEvals)
	{
		$this->bestDecadePosition = array();
		$this->searchSpaceSize = array();
		for ($i = 0; ($i < $this->problemDimension); ++$i) 
		{
			$this->searchSpaceSize[$i] = ($this->maxBounds[$i] - $this->minBounds[$i]);
		}
		$this->initialCountries = $this->createNewCountries($this->numOfCountries, $this->problemDimension, $this->minBounds, $this->maxBounds, $this->r);
		$this->initialCosts = $this->countriesCosts($this->initialCountries);
		$this->sortCountries($this->initialCosts, $this->initialCountries);
		$this->createEmpires();
		$lastDecade = 0;
		for ($decade = 0; ($decade < $this->numOfDecades); ++$decade) 
		{
			if (($this->fitnessFunc->nbEvals < $maxEvals))
			{
				$this->revolutionRate = ($this->dampRatio * $this->revolutionRate);
				for ($i = 0; ($i < count($this->empiresList) ); ++$i) 
				{
					$this->assimilationPolicy($this->empiresList[$i]);
					$this->revolutionProcess($this->empiresList[$i]);
					$this->empiresList[$i]->setColoniesCost($this->countriesCosts($this->empiresList[$i]->getColoniesPosition()));
					$this->possesEmpire($this->empiresList[$i]);
					$this->empiresList[$i]->setTotalCost(($this->empiresList[$i]->getImperialistCost() + ($this->zeta * $this->utils->getMean($this->empiresList[$i]->getColoniesCost()))));
				}
				$this->uniteSimilarEmpires();
				$this->imperialisticCompetition();
				if (((count($this->empiresList)   == 1) && $this->stopIfJustOneEmpire))
				{
					break;
				}
				$imperialistCosts = array();
				for ($i = 0; ($i < count($this->empiresList)  ); ++$i)
				{
					$imperialistCosts[$i] = $this->empiresList[$i]->getImperialistCost();
				}
				$this->minimumCost[$decade] = $imperialistCosts[$this->utils->getMinIndex($imperialistCosts)];
				$this->meanCost[$decade] = $this->utils->getMean($imperialistCosts);
				$this->bestDecadePosition[$decade] = $this->empiresList[$this->utils->getMinIndex($imperialistCosts)]->getImperialistPosition();
				$lastDecade = $decade;
			}
		}
		$minimumCostRedux = $Arrays->copyOf($this->minimumCost, ($lastDecade + 1));
		$bestIndex = $this->utils->getMinIndex($minimumCostRedux);
		$bestSolution = $this->bestDecadePosition[$bestIndex];
		return $bestSolution;
	}
	protected function createNewCountries ($numberOfCountries, $dimension, $minVector, $maxVector, $rand)
	{
		$countriesArray = array();
		for ($i = 0; ($i < $numberOfCountries); ++$i) 
		{
			for ($j = 0; ($j < $dimension); ++$j) 
			{
				$countriesArray[$i][$j] = (((($maxVector[$j] - $minVector[$j])) * $rand->nextDouble()) + $minVector[$j]);
			}
		}
		return $countriesArray;
	}
	protected function countriesCosts ($countriesArray)
	{
		$costsVector = array();
		for ($i = 0; ($i < count($countriesArray) ); ++$i)
		{
			$costsVector[$i] = $this->fitnessFunc->getFitnessValue($countriesArray[$i]);
		}
		return $costsVector;
	}
	protected function sortCountries ($arrayToSort, $matchingArray)
	{
		$sortOrder = array();
		for ($i = 0; ($i < count($sortOrder)  ); ++$i)
		{
			$sortOrder[$i] = $i;
		}
		$Arrays->sort($sortOrder, new Comparator());
		$arrayToSortCopy = $arrayToSort->clone();
		$matchingArrayCopy = $matchingArray->clone();
		for ($i = 0; ($i < count($sortOrder)  ); ++$i)
		{
			$this->initialCosts[$i] = $arrayToSortCopy[$sortOrder[$i]];
			$this->initialCountries[$i] = $matchingArrayCopy[$sortOrder[$i]];
		}
	}
	protected function createEmpires () 
	{
		$allImperialistsPosition = $this->utils->extractArrayRange($this->initialCountries, 0, $this->numOfInitialImperialists);
		$allImperialistsCost = array();
		foreach (range(0, ($this->numOfInitialImperialists + 0)) as $_upto) $allImperialistsCost[$_upto] = $this->initialCosts[$_upto - (0) + 0]; /* from: System.arraycopy(initialCosts, 0, allImperialistsCost, 0, numOfInitialImperialists) */;
		$allColoniesPosition = $this->utils->extractArrayRange($this->initialCountries, $this->numOfInitialImperialists, count($this->initialCountries) /*from: initialCountries.length*/);
		$allColoniesCost = array();
		foreach (range(0, ((count($this->initialCosts) ;
		$allImperialistsPower = array();
		if (($allImperialistsCost[$this->utils->getMaxIndex($allImperialistsCost)] > 0))
		{
			for ($i = 0; ($i < count($allImperialistsCost)  ); ++$i)
			{
				$allImperialistsPower[$i] = ((doubleval(1.3) * $allImperialistsCost[$this->utils->getMaxIndex($allImperialistsCost)]) - $allImperialistsCost[$i]);
			}
		}
		else
		{
			for ($i = 0; ($i < count($allImperialistsCost) ); ++$i)
			{
				$allImperialistsPower[$i] = ((doubleval(0.7) * $allImperialistsCost[$this->utils->getMaxIndex($allImperialistsCost)]) - $allImperialistsCost[$i]);
			}
		}
		$allImperialistNumOfColonies = array();
		for ($i = 0; ($i < count($allImperialistsPower) ); ++$i)
		{
			$allImperialistNumOfColonies[$i] = round(($allImperialistsPower[$i] / $me->utils->getSum_aD($allImperialistsPower)) * $me->numOfAllColonies);
		}
		$allImperialistNumOfColonies[(count($allImperialistNumOfColonies)  - 1)))), 0;
		for ($i = 0; ($i < $this->numOfInitialImperialists); ++$i) 
		{
			$this->empiresList[$i] = Empire::constructor__I($this->problemDimension);
		}
		$randomIndex = $this->utils->randperm($this->numOfAllColonies, $this->r);
		for ($i = 0; ($i < $this->numOfInitialImperialists); ++$i) 
		{
			$R = $Arrays->copyOfRange($randomIndex, 0, $allImperialistNumOfColonies[$i]);
			$this->empiresList[$i]->init(count($R) );
			$randomIndex = $Arrays->copyOfRange($randomIndex, $allImperialistNumOfColonies[$i], count($randomIndex)  );
			$this->empiresList[$i]->setImperialistPosition($allImperialistsPosition[$i]);
			$this->empiresList[$i]->setImperialistCost($allImperialistsCost[$i]);
			$this->empiresList[$i]->setColoniesPosition($this->utils->extractGivenArrayParts($allColoniesPosition, $R));
			$this->empiresList[$i]->setColoniesCost($this->utils->extractGivenArrayParts($allColoniesCost, $R));
			$this->empiresList[$i]->setTotalCost(($this->empiresList[$i]->getImperialistCost() + ($this->zeta * $this->utils->getMean($this->empiresList[$i]->getColoniesCost()))));
		}
		for ($i = 0; ($i < count($this->empiresList) ); ++$i)
		{
			if ((count($this->empiresList[$i]->getColoniesPosition())  == 0))
			{
				$this->empiresList[$i]->setColoniesPosition($this->createNewCountries(1, $this->problemDimension, $this->minBounds, $this->maxBounds, $this->r));
				$this->empiresList[$i]->setColoniesCost($this->countriesCosts($this->empiresList[$i]->getColoniesPosition()));
			}
		}
	}
	protected function assimilationPolicy ($theEmpire) 
	{
		$numOfColonies = count($theEmpire->getColoniesPosition()) ;
		$repmatArray = $this->utils->repmat($theEmpire->getImperialistPosition(), $numOfColonies);
		$array = array();
		for ($i = 0; ($i < $numOfColonies); ++$i) 
		{
			for ($j = 0; ($j < $this->problemDimension); ++$j) 
			{
				$array[$i][$j] = ($repmatArray[$i][$j] - $theEmpire->getColoniesPosition()[$i][$j]);
			}
		}
		$coloniesPosition = array();
		for ($i = 0; ($i < count($array) ); ++$i) 
		{
			for ($j = 0; ($j < count($array[0]) ); ++$j) 
			{
				$coloniesPosition[$i][$j] = ($theEmpire->getColoniesPosition()[$i][$j] + (((2 * $this->assimilationCoefficient) * $this->r->nextDouble()) * $array[$i][$j]));
			}
		}
		$theEmpire->setColoniesPosition($coloniesPosition);
		$minVarMatrix = $this->utils->repmat($this->minBounds, $numOfColonies);
		$maxVarMatrix = $this->utils->repmat($this->maxBounds, $numOfColonies);
		$theEmpire->setColoniesPosition($this->utils->max($theEmpire->getColoniesPosition(), $minVarMatrix));
		$theEmpire->setColoniesPosition($this->utils->min($theEmpire->getColoniesPosition(), $maxVarMatrix));
	}
	protected function revolutionProcess ($theEmpire)  
	{
		$numOfRevolvingColonies = round(($me->revolutionRate * count($theEmpire->getColoniesCost())
		$revolvedPosition = $this->createNewCountries($numOfRevolvingColonies, $this->problemDimension, $this->minBounds, $this->maxBounds, $this->r);
		$R = $this->utils->randperm(count($theEmpire->getColoniesCost()) , $this->r);
		$R = $Arrays->copyOfRange($R, 0, $numOfRevolvingColonies);
		for ($i = 0; ($i < count($R) ); ++$i)
		{
			$theEmpire->setColonyPosition($R[$i], $revolvedPosition[$i]);
		}
	}
	protected function possesEmpire ($theEmpire)  
	{
		$coloniesCost = $theEmpire->getColoniesCost();
		$bestColonyInd = $this->utils->getMinIndex($coloniesCost);
		$minColoniesCost = $coloniesCost[$bestColonyInd];
		if (($minColoniesCost < $theEmpire->getImperialistCost()))
		{
			$oldImperialistPosition = $theEmpire->getImperialistPosition();
			$oldImperialistCost = $theEmpire->getImperialistCost();
			$theEmpire->setImperialistPosition($theEmpire->getColoniesPosition()[$bestColonyInd]);
			$theEmpire->setImperialistCost($theEmpire->getColoniesCost()[$bestColonyInd]);
			$theEmpire->setColonyPosition($bestColonyInd, $oldImperialistPosition);
			$theEmpire->setColonyCost($bestColonyInd, $oldImperialistCost);
		}
	}
	//Uniting Similar Empires
	protected function uniteSimilarEmpires () 
	{
		$thresholdDistance = ($this->unitingThreshold * $this->utils->getNorm($this->searchSpaceSize));
		$numOfEmpires = count($this->empiresList) ;
		for ($i = 0; ($i < (($numOfEmpires - 1))); ++$i) 
		{
			for ($j = ($i + 1); ($j < $numOfEmpires); ++$j) 
			{
				$distanceVector = array();
				for ($k = 0; ($k < count($this->empiresList[$i]->getImperialistPosition()) ); ++$k) 
				{
					$distanceVector[$k] = ($this->empiresList[$i]->getImperialistPosition()[$k] - $this->empiresList[$j]->getImperialistPosition()[$k]);
				}
				$distance = $this->utils->getNorm($distanceVector);
				if (($distance <= $thresholdDistance))
				{
					$betterEmpireInd = null;
					$worseEmpireInd = null;
					if (($this->empiresList[$i]->getImperialistCost() < $this->empiresList[$j]->getImperialistCost()))
					{
						$betterEmpireInd = $i;
						$worseEmpireInd = $j;
					}
					else
					{
						$betterEmpireInd = $j;
						$worseEmpireInd = $i;
					}
					$newColoniesPosition = $this->newColonyPositionAfterUniting($betterEmpireInd, $worseEmpireInd);
					$this->empiresList[$betterEmpireInd]->setColoniesPosition($newColoniesPosition);
					$newColoniesCost = $this->getNewColonyCostsAfterUniting($betterEmpireInd, $worseEmpireInd);
					$this->empiresList[$betterEmpireInd]->setColoniesCost($newColoniesCost);
					$this->empiresList[$betterEmpireInd]->setTotalCost(($this->empiresList[$betterEmpireInd]->getImperialistCost() + ($this->zeta * $this->utils->getMean($this->empiresList[$betterEmpireInd]->getColoniesCost()))));
					$this->empireElimination($worseEmpireInd);
					return ;
				}
			}
		}
	}
	// Colony Costs Of United Empire
	protected function NewColonyCostsAfterUniting ($betterEmpireInd, $worseEmpireInd) 
	{
		$newColoniesCount = ((count($this->empiresList[$betterEmpireInd]->getColoniesCost())   + 1) + count($this->empiresList[$worseEmpireInd]->getColoniesCost()) /*from: empiresList[worseEmpireInd].getColoniesCost().length*/);
		$newColoniesCost = array();
		$i = null;
		for ($i = 0; ($i < count($this->empiresList[$betterEmpireInd]->getColoniesCost()) ); ++$i) 
		{
			$newColoniesCost[$i] = $this->empiresList[$betterEmpireInd]->getColoniesCost()[$i];
		}
		$newColoniesCost[$i] = $this->empiresList[$worseEmpireInd]->getImperialistCost();
		$i2 = null;
		for ($i2 = ($i + 1); ($i2 < $newColoniesCount); ++$i2) 
		{
			$newColoniesCost[$i2] = $this->empiresList[$worseEmpireInd]->getColoniesCost()[(($i2 - count($this->empiresList[$betterEmpireInd]->getColoniesCost()) /*from: empiresList[betterEmpireInd].getColoniesCost().length*/) - 1)];
		}
		return $newColoniesCost;
	}
	// Colony Positions Of United Empire
	protected function newColonyPositionAfterUniting ($betterEmpireInd, $worseEmpireInd)  
	{
		$newSize = ((count($this->empiresList[$betterEmpireInd]->getColoniesPosition())  + 1) + count($this->empiresList[$worseEmpireInd]->getColoniesPosition()) /*from: empiresList[worseEmpireInd].getColoniesPosition().length*/);
		$newColoniesPosition = array();
		$i = null;
		for ($i = 0; ($i < count($this->empiresList[$betterEmpireInd]->getColoniesPosition()) ); ++$i) 
		{
			$newColoniesPosition[$i] = $this->empiresList[$betterEmpireInd]->getColoniesPosition()[$i];
		}
		$newColoniesPosition[$i] = $this->empiresList[$worseEmpireInd]->getImperialistPosition();
		$i2 = null;
		for ($i2 = ($i + 1); ($i2 < $newSize); ++$i2) 
		{
			$newColoniesPosition[$i2] = $this->empiresList[$worseEmpireInd]->getColoniesPosition()[(($i2 - count($this->empiresList[$betterEmpireInd]->getColoniesPosition()) /*from: empiresList[betterEmpireInd].getColoniesPosition().length*/) - 1)];
		}
		return $newColoniesPosition;
	}
	protected function imperialisticCompetition () 
	{
		$rand = $this->r->nextDouble();
		if (($rand > doubleval(.11)))
		{
			return ;
		}
		if ((count($this->empiresList)   <= 1))
		{
			return ;
		}
		$totalCosts = array();
		for ($i = 0; ($i < count($this->empiresList)  ); ++$i) 
		{
			$totalCosts[$i] = $this->empiresList[$i]->getTotalCost();
		}
		$weakestEmpireInd = $this->utils->getMaxIndex($totalCosts);
		$maxTotalCost = $totalCosts[$weakestEmpireInd];
		$totalPowers = array();
		for ($i = 0; ($i < count($this->empiresList)  ); ++$i) 
		{
			$totalPowers[$i] = ($maxTotalCost - $totalCosts[$i]);
		}
		$possessionProbability = array();
		for ($i = 0; ($i < count($this->empiresList)  ); ++$i) 
		{
			$possessionProbability[$i] = ($totalPowers[$i] / $this->utils->getSum_aD($totalPowers));
		}
		$selectedEmpireInd = $this->emireSelection($possessionProbability);
		$numOfColoniesOfWeakestEmpire = count($this->empiresList[$weakestEmpireInd]->getColoniesCost()) ;
		$indexOfSelectedColony = $this->r->nextInt($numOfColoniesOfWeakestEmpire);
		$this->empiresList[$selectedEmpireInd]->setColoniesPosition($this->positionsConcatenation($this->empiresList[$selectedEmpireInd]->getColoniesPosition(), $this->empiresList[$weakestEmpireInd]->getColoniesPosition()[$indexOfSelectedColony]));
		$this->empiresList[$selectedEmpireInd]->setColoniesCost($this->costConcatenation($this->empiresList[$selectedEmpireInd]->getColoniesCost(), $this->empiresList[$weakestEmpireInd]->getColoniesCost()[$indexOfSelectedColony]));
		$this->empiresList[$weakestEmpireInd]->setColoniesPosition($this->removeColonyPosition($this->empiresList[$weakestEmpireInd]->getColoniesPosition(), $indexOfSelectedColony));
		$this->empiresList[$weakestEmpireInd]->setColoniesCost($this->removeColonyCost($this->empiresList[$weakestEmpireInd]->getColoniesCost(), $indexOfSelectedColony));
		$numOfColoniesOfWeakestEmpire = count($this->empiresList[$weakestEmpireInd]->getColoniesCost()) ;
		if (($numOfColoniesOfWeakestEmpire <= 1))
		{
			$this->empiresList[$selectedEmpireInd]->setColoniesPosition($this->positionsConcatenation($this->empiresList[$selectedEmpireInd]->getColoniesPosition(), $this->empiresList[$weakestEmpireInd]->getImperialistPosition()));
			$this->empiresList[$selectedEmpireInd]->setColoniesCost($this->costConcatenation($this->empiresList[$selectedEmpireInd]->getColoniesCost(), $this->empiresList[$weakestEmpireInd]->getImperialistCost()));
			$this->empireElimination($weakestEmpireInd);
		}
	}
	protected function removeColonyPosition ($colonyPositions, $indexToRemove) 
	{
		$newColonyPositions = array();
		for ($i = 0; ($i < $indexToRemove); ++$i) 
		{
			$newColonyPositions[$i] = $colonyPositions[$i];
		}
		for ($j = $indexToRemove; ($j < count($newColonyPositions)  ); ++$j) 
		{
			$newColonyPositions[$j] = $colonyPositions[($j + 1)];
		}
		return $newColonyPositions;
	}
	protected function removeColonyCost ($colonyCosts, $indexToRemove) 
	{
		$newColonyCosts = array();
		for ($i = 0; ($i < $indexToRemove); ++$i) 
		{
			$newColonyCosts[$i] = $colonyCosts[$i];
		}
		for ($j = $indexToRemove; ($j < count($newColonyCosts)  ); ++$j) 
		{
			$newColonyCosts[$j] = $colonyCosts[($j + 1)];
		}
		return $newColonyCosts;
	}
	// concatenate Positions
	protected function positionsConcatenation ($positions1, $position2)  
	{
		$newPositions = array();
		$i = null;
		for ($i = 0; ($i < count($positions1)  ); ++$i) 
		{
			$newPositions[$i] = $positions1[$i];
		}
		$newPositions[$i] = $position2;
		return $newPositions;

	}
	// concatenate Costs
	protected function costConcatenation ($costs1, $cost2)  
	{
		$newCosts = array();
		$i = null;
		for ($i = 0; ($i < count($costs1) ); ++$i) 
		{
			$newCosts[$i] = $costs1[$i];
		}
		$newCosts[$i] = $cost2;
		return $newCosts;
	}
	//delete An Empire
	protected function empireElimination ($indexToDelete)  
	{
		$empiresList1 = $Arrays->copyOfRange($this->empiresList, 0, $indexToDelete);
		$empiresList2 = $Arrays->copyOfRange($this->empiresList, ($indexToDelete + 1), count($this->empiresList) /*from: empiresList.length*/);
		$this->empiresList = array();
		for ($n = 0; ($n < ((count($empiresList1)  + count($empiresList2) ))); ++$n) 
		{
			if (($n < count($empiresList1) ))
			{
				$this->empiresList[$n] = $empiresList1[$n];
			}
			if (($n >= count($empiresList1) ))
			{
				$this->empiresList[$n] = $empiresList2[($n - count($empiresList1)  )];
			}
		}
	}
	//select an Empire 
	protected function emireSelection ($probability)  
	{
		$randVector = array();
		for ($i = 0; ($i < count($probability) ); ++$i) 
		{
			$randVector[$i] = $this->r->nextDouble();
		}
		$dVector = array();
		for ($i = 0; ($i < count($probability) ); ++$i) 
		{
			$dVector[$i] = ($probability[$i] - $randVector[$i]);
		}
		return $this->utils->getMaxIndex($dVector);
	}
	public function reset () 
	{
		$this->seed = round(microtime(true) * 1000);
		$this->r = new Random($this->seed);
		$this->empiresList = array();
		$this->minimumCost = array();
		$this->meanCost = array();
		$this->fitnessFunc->nbEvals = 0;
	}

}
?>
