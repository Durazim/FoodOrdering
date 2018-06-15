<pre>
<?php
    require('../loginTest.php');
    if(isset($arr))
    {
        if ($arr[0] == 'restaurant')
        {
          header('location:../index.php');
          die();
        }
    }

    if(!isset($_SESSION['order']))
    {
        header('location:../order.php');
        die();
    }

    $rid = $_SESSION['order']['rid'];

    try
    {
        require('connection.php');
        $db->beginTransaction();

        $totalp=0.500;
        foreach ($_SESSION['order']['dishes'] as $did => $qty)
        {
            $rs=$db->query("select Price from dish where rid=$rid and DID=$did");
            $rs=$rs->fetch(PDO::FETCH_OBJ);
            $totalp+=$qty*$rs->Price;
        }

        $d=mktime(date('H')+1,date('i'),date('s'),date('m'),date('d'),date('Y'));
        $t=date('Y/m/d H:i:s',$d);
        print_r($t);

        $order = $db->prepare("INSERT INTO orderslist VALUES (null, $rid, $arr[2], '1', 'acknowledge', $totalp, '$t')");
        $order->execute();
        $insertId = $db->lastInsertId();

        $orderd = $db->prepare("INSERT INTO orderdishes VALUES ($insertId,:dish,:qty)");
        $orderd->bindparam(':dish',$did);
        $orderd->bindparam(':qty',$qty);
        foreach ($_SESSION['order']['dishes'] as $did => $qty)
        {
            $orderd->execute();
            unset($_SESSION['order']['dishes'][$did]);
            if(count($_SESSION['order']['dishes']) == 0)
            {
              unset($_SESSION['order']);
            }
        }

        header('location:../myOrders.php');

        $db->commit();
    }
  catch (PDOException $ex)
  {
    die($ex->getMessage());
  }


?>
</pre>
