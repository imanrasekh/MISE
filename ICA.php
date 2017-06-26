
<?php

$num_of_empires = 4;


class Empire
{
    public $colonies;
    public $avgColony;


    function FindImperialists($maxX, $maxY)
    {
         $xsum=0;
         $ysum=0;
        if (count($this->colonies)==0)
        {
            $this->avgColony->x = rand(0, $maxX);
            $this->avgColony->y =  rand(0,$maxY);

            return;
        }

        foreach($this->colonies as $p)
        {
            $xsum += $p->x;
            $ysum += $p->y;

        }

        $count = count($this->colonies);
        $this->avgColony->x =  $xsum / $count;
        $this->avgColony->y =  $ysum / $count;

    }
}
class Colony
{
    public $x;
    public $y;
    function getDistance($p)
    {

        $x1 = $this->x - $p->x;
        $y1 = $this->y - $p->y;
        return sqrt($x1*$x1 + $y1*$y1);
    }
}

function ColonyAssignment($k, $arr)
{
    $maxX=0;
    $maxY=0;
    foreach($arr as $p)
    {
       if ($p->x > $maxX)
        $maxX = $p->x;
      if ($p->y > $maxY)
            $maxY = $p->y;
    }
    $empires = array();
    for($i = 0; $i < $k; $i++)
    {

        $empires[] = new Empire();
        $tmpP = new Colony();
        $tmpP->x=rand(0,$maxX);
        $tmpP->y=rand(0,$maxY);
        $empires[$i]->avgColony = $tmpP;
    }

    for ($a = 0; $a < 200; $a++)
    {
        foreach($empires as $empire)
            $empire->colonies = array(); //reinitialize
        foreach($arr as $pnt)
        {

            $bestempire=$empires[0];

            $bestdist = $empires[0]->avgColony->getDistance($pnt);


            foreach($empires as $empire)
            {

                if ($empire->avgColony->getDistance($pnt) < $bestdist)
                {

                    $bestempire = $empire;

                    $bestdist = $empire->avgColony->getDistance($pnt);

                }
            }

            $bestempire->colonies[] = $pnt;

        }

        foreach($empires as $empire)
            $empire->FindImperialists($maxX, $maxY);

    }
    return $empires;
}

/////////////////////////////

?>