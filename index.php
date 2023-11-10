<?php
   // tell server that you will be tracking session variables
   session_start( );
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>my inventory</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

    <?php
// the filename of the currently executing script to be used as the action = " " attribute of the form element.
$self = $_SERVER['PHP_SELF'];

// check to see if this is the first time viewing the page, the hidSubmitFlag will not exist if this is the first time
if(array_key_exists('hidSubmitFlag', $_POST))
{
   echo "<h2>Welcome back!</h2>";

   // look at the hidden submitFlag variable to determine what to do
   $submitFlag = $_POST['hidSubmitFlag'];

   // get the array that was stored as a session variable
   $invenArray = unserialize(urldecode($_SESSION['serializedArray']));
   switch($submitFlag)
   {
      case "01": addRecord( );
      break;

      case "99": deleteRecord( );
      break;

      default: displayInventory($invenArray);
   }
} 

else {
   echo "<h2>Welcome to the Inventory Page</h2>";
   // first time visitor? If so, create the array
   $invenArray = array( );
   $invenArray[0][0] ="1111";
   $invenArray[0][1] ="Rose";
   $invenArray[0][2] ="1.95";
   $invenArray[0][3] ="100";

   $invenArray[1][0] ="2222";
   $invenArray[1][1] ="Dandelion Tree";
   $invenArray[1][2] ="2.95";
   $invenArray[1][3] ="200";

   $invenArray[2][0] ="3333";
   $invenArray[2][1] ="Crabgrass Bush";
   $invenArray[2][2] ="3.95";
   $invenArray[2][3] ="300";

   // save this array as a serialized session variable
   $_SESSION['serializedArray'] = urlencode(serialize($invenArray));
}

function addRecord( )
{
   global $invenArray;
   // add the new information into the array
   $invenArray[ ] = array($_POST['txtPartNo'],
                          $_POST['txtDescr'],
                          $_POST['txtPrice'],
                          $_POST['txtQty']);

   // the sort will be on the first column (part number) so use this to re-order the displays
   sort($invenArray);

   // save the updated array in its session variable
   $_SESSION['serializedArray'] = urlencode(serialize($invenArray));
}

function deleteRecord( )
{
   global $invenArray;
   global $deleteMe;

   // get the selected index from the lstItem
   $deleteMe = $_POST['lstItem'];

   unset($invenArray[$deleteMe]);

   // Save the updated array in its session variable
   $_SESSION['serializedArray'] = urlencode(serialize($invenArray));
}

function displayInventory( )
{
   global $invenArray;

   echo "<table border='1'>";
   // display the header
   echo "<tr>";
   echo "<th>Part No.</th>";
   echo "<th>Description</th>";
   echo "<th>Price</th>";
   echo "<th>Qty</th>";
   echo "</tr>";

   // walk through each record or row
   foreach($invenArray as $record)
   {
      echo "<tr>";

      // for each column in the row
      foreach($record as $value)
      {
         echo "<td>$value</td>";
      }
      echo "</tr>";
   }

   // stop the table
   echo "</table>";
}

?>
    <img src="graphic/tree.jpeg" alt="photo of tree branch with leaves" />
    <h1>plants you-nique</h1>

    <h2>here is our current inventory:</h2>
    <?php displayInventory( ); ?>
    </p>
    <form action="<?php $self ?>" method="POST" name="frmAdd">
        <fieldset id="fieldsetAdd">
            <legend>add an item</legend>
            <label for="txtPartNo">part num:</label>
            <input type="text" name="txtPartNo" id="txtPartNo" value="999" size="5" />
            <br /><br />
            <label for="txtDescr">description:</label>
            <input type="text" name="txtDescr" id="txtDescr" value="Test Descr" />
            <br /><br />
            <label for="txtPrice">price in usd</label>
            <input type="text" name="txtPrice" id="txtPrice" value="123.45" />
            <br /><br />
            <label for="txtQty">quantity available</label>
            <input type="text" name="txtQty" id="txtQty" value="123" size="5" />
            <br /><br />

            <!-- this field is used to determine if the page has been viewed already
           Code 01 = Add -->
            <input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='01' />
            <input name="btnSubmit" type="submit" value="add this information" />
        </fieldset>
    </form>
    <br><br>

    <form action="<?php $self ?>" method="POST" name="frmDelete">
        <fieldset id="fieldsetDelete">

            <legend>select an item to delete</legend>
            <select name="lstItem" size="1">
                <?php
                
      // populate the list box using data from the array
      foreach($invenArray as $index => $lstRecord)
      {
        // make the value the index and the text displayed the description from the array, then use the index to delete the record
         echo "<option value='".$index."'>".$lstRecord[1]."</option>\n";
      }
?>
            </select>

            <!-- this field is used to determine if the page has been viewed already code 99 = delete-->
            <input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='99' />
            <br><br>

            <input name="btnSubmit" type="submit" value="delete this selection" />
        </fieldset>
    </form>
</body>
</html>
