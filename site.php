<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

      <?php


        echo("<h1>Amortization Calculator</h1>");
        echo("<hr>");
        echo("<p> Input your values below. </p>");

       ?>
       <form action="site.php" method="get">
       Amount of Loan: <input type="text" name = "startprince">
       <br>
       Interest Rate (%): <input type = "text" name = "yearlyrate">
       <br>
       Years to pay off: <input type="number" name = "nyears">
       <br>

       <input type="submit" value = "Submit">
       </form>
       <br>
       <?php


          if($_GET["startprince"] != null){
          $nmonths = $n*12;
          $array0 = array_fill(0,$nmonths,0);
          $array1 = array_fill(0,$nmonths,0);
          $array2 = array_fill(0,$nmonths,0.0);
          $array3 = array_fill(0,$nmonths,0.0);
          $array4 = array_fill(0,$nmonths,0.0);
          $mastarray = array_fill(0,$nmonths,null);
          $princestart = floatval($_GET["startprince"]);
          $mrate = floatval($_GET["yearlyrate"])/1200.0;
          $n = $_GET["nyears"];
          function calculateMonthlyPayment($mratein,$principlein,$nin){

              return $principlein * (($mratein*pow(1.0+$mratein,12*$nin))/(pow(1.0+$mratein,12*$nin)-1));
          }
          function calculateTotalInterestPaid($monthlypaymentin,$principlein,$nin){
              return ($monthlypaymentin*12.0*$nin)-$principlein;
          }
          $monthlypayment=round(calculateMonthlyPayment($mrate,$princestart,$n),2);
          $totalinterestpaid = round(calculateTotalInterestPaid($monthlypayment,$princestart,$n),2);
          $totalowed = round($princestart + $totalinterestpaid,2);
          $interestremaining = $totalinterestpaid;
          $principleremaining = $princestart;

          function calculateSplit($premin,$iremin,$mpayin){
              $splitty = array(0.0,0.0);
              // principleleft,interestleft.

              if($iremin != 0.0){
                  if($iremin <= $mpayin){
                      $splitty[0] = $premin - round($mpayin-$iremin,2);
                  } else {
                      $splitty[0] = $premin;
                      $splitty[1] = round($iremin-$mpayin,2);
                  }
              } else {
                  $splitty[1] = 0.0;
                  $splitty[0] = round($premin - $mpayin,2);

              }
              return $splitty;
          }
          $cmonth = 0;
          for($x = 0; $x < $n; $x++){

            for($y = 0; $y < 12; $y++){
                $splito=calculateSplit($principleremaining,$interestremaining,$monthlypayment);
                $principleremaining = $splito[0];
                $interestremaining = $splito[1];
                $yearval = $x+1;
                $monthval = $y+1;
                $dateObj   = DateTime::createFromFormat('!m', $monthval);
                $monthName = $dateObj->format('F'); // March
                $array0[$cmonth] = $yearval;
                $array1[$cmonth] = $monthName;
                $array2[$cmonth] = $principleremaining + $interestremaining;
                $array3[$cmonth] = $principleremaining;
                $array4[$cmonth] = $interestremaining;
                $owedcur = $principleremaining + $interestremaining;
                $owedcurstr = "$$owedcur";
                $pstr = "$$principleremaining";
                $istr = "$$interestremaining";
                $mastarray[$cmonth] = array("yearval" => $yearval,"monthval" => $monthName, "amountowed" =>$owedcurstr,"principleleft"=>
              $pstr,"interestleft" => $istr );
                $cmonth++;
                if(false){
                echo("Year = $yearval Month = $monthval PrincipleRemaining = $principleremaining InterestRemaining = $interestremaining");
                echo("<br>");
                }
            }
            if(false){
            echo("<hr>");
            }
          }
          echo("<br>");
          echo("Monthly Payment: $$monthlypayment");
          echo("<br>");
          echo($totalinterestpaid);
          echo("<br>");
          if (count($mastarray) > 0): ?>
  <table>
    <thead>
      <tr>
        <th><?php echo implode('</th><th>', array_keys(current($mastarray))); ?></th>
      </tr>
    </thead>
    <tbody>
  <?php foreach ($mastarray as $row): array_map('htmlentities', $row); ?>
      <tr>
        <td><?php echo implode('</td><td>', $row); ?></td>
      </tr>
  <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif;
        }
        ?>
        <?php  ?>


  </body>
</html>
