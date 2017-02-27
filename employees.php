
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<title>Employee's Details</title>
  	<link rel="stylesheet" type="text/css" href="view.css" media="all">

    <style>
      .error {color: #FF0000;}
    </style>
  </head>

  <body id="main_body" >

    	<img id="top" src="top.png" alt="">
    	<div id="form_container">

    		<h1>
          <a>Employee's Details</a>
        </h1>

<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('html_errors', true);
  class Contact
  {
      private $xmlPath;
      private $domDocument;

      public function __construct($xmlPath) {
          // instantiate the private variable representing the DOMDocument
          // loads the document
          $doc = new DOMDocument();
          $doc->load($xmlPath);

          // is this a employees xml file?
          if ($doc->doctype->name != "employees" ||
              $doc->doctype->systemId != "employees.dtd") {
              throw new Exception("Incorrect document type");
          }

          // is the document valid and well-formed?
          if($doc->validate()) {
              $this->domDocument = $doc;
              $this->xmlPath = $xmlPath;
              //echo 'alert("valid")'; echo '</script>';
          }
          else {
              throw new Exception("Document did not validate");
          }
      }

      public function __destruct() {
          // free memory associated with the DOMDocument
          //echo 'alert("destruct")'; echo '</script>';
          unset($this->domDocument);
      }

      public function getThemAll(){
        //echo 'alert("all")'; echo '</script>';
        $query = '//employees/employee/.';
        //$employees = $this->domDocument->getElementsByTagName('employee');

        // create a new XPath object and associate it with the document we want to query against
        $xpath = new DOMXPath($this->domDocument);
        $result = $xpath->query($query);
        $arrEmps = array();
        if($result->length){
          // iterate of the results
          $classCounter = 0;
          foreach($result as $emp)  {
              // add the details of the employee to an array
              $arrEmps[$classCounter]["name"] = $emp->getElementsByTagName("name")->item(0)->nodeValue;
              //echo $arrEmps[$classCounter]["name"]."<br><hr>";
              $arrEmps[$classCounter]["gender"]  = $emp->getElementsByTagName("gender")->item(0)->nodeValue;
              //echo $arrEmps[$classCounter]["gender"]."<br><hr>";
              $arrEmps[$classCounter]["phone"]  = $emp->getElementsByTagName("phone")->item(0)->nodeValue;
              //echo $arrEmps[$classCounter]["phone"]."<br><hr>";
              $arrEmps[$classCounter]["email"] = $emp->getElementsByTagName("email")->item(0)->nodeValue;
              //echo $arrEmps[$classCounter]["email"]."<br><hr>";
              $classCounter +=1;
              //echo $classCounter."<br><hr>";
          }
        }
        return $arrEmps;
      }

      public function findEmployeeByName($name) {
          // Return an array of employees
          // use XPath to find the employee we're looking for
          $query = '//employees/employee/name[text() = "' . $name . '"]/..';

          // create a new XPath object and associate it with the document we want to query against
          $xpath = new DOMXPath($this->domDocument);
          $result = $xpath->query($query);
          $arrEmps = array();
          if($result->length){
            // iterate of the results
            $classCounter = 0;
            foreach($result as $emp)  {
                // add the details of the employee to an array
                $arrEmps[$classCounter]["name"] = $emp->getElementsByTagName("name")->item(0)->nodeValue;
                $arrEmps[$classCounter]["gender"]  = $emp->getElementsByTagName("gender")->item(0)->nodeValue;
                $arrEmps[$classCounter]["phone"]  = $emp->getElementsByTagName("phone")->item(0)->nodeValue;
                $arrEmps[$classCounter]["email"] = $emp->getElementsByTagName("email")->item(0)->nodeValue;
                $classCounter++;
            }
        }
        return $arrEmps;
      }

      public function findEmployeeByEmail($email) {
          // Return an array of employees
          // use XPath to find the employee we're looking for
          $query = '//employees/employee/email[text() = "' . $email . '"]/..';

          // create a new XPath object and associate it with the document we want to query against
          $xpath = new DOMXPath($this->domDocument);
          $result = $xpath->query($query);
          $arrEmps = array();
          if($result->length){
            // iterate of the results
            $classCounter = 0;
            foreach($result as $emp)  {
                // add the details of the employee to an array
                $arrEmps[$classCounter]["name"] = $emp->getElementsByTagName("name")->item(0)->nodeValue;
                $arrEmps[$classCounter]["gender"]  = $emp->getElementsByTagName("gender")->item(0)->nodeValue;
                $arrEmps[$classCounter]["phone"]  = $emp->getElementsByTagName("phone")->item(0)->nodeValue;
                $arrEmps[$classCounter]["email"] = $emp->getElementsByTagName("email")->item(0)->nodeValue;
                $classCounter++;
          }
        }
        return $arrEmps;
      }

      public function addEmployee($name, $gender, $phone, $email) {
          // add employee to the database
          // create a new element represeting the new book
          //$contact = $this->domDocument->documentElement;
          $newEmp = $this->domDocument->createElement("employee");
          // append the newly created element
          $this->domDocument->documentElement->appendChild($newEmp);

          $name = $this->domDocument->createElement("name", $name);
          $newEmp->appendChild($name);

          $gender = $this->domDocument->createElement("gender", $gender);
          $newEmp->appendChild($gender);

          $phone = $this->domDocument->createElement("phone", $phone);
          $newEmp->appendChild($phone);

          $email = $this->domDocument->createElement("email", $email);
          $newEmp->appendChild($email);

          //$contact->appendChild($newEmp);

          //$this->domDocument->appendChild($contact);
          // save the document
          //header("Content-type: text/xml");
          $this->domDocument->saveXML();
          $this->domDocument->save($this->xmlPath);
      }

      public function deleteEmployee($name) {
        $result = false;
        // Delete employee from the database
        // get the employee element based on name
        // use XPath to find the employee we're looking for
        $query = '//employees/employee/name[text() = "' . $name . '"]/..';

        $xpath = new DOMXPath($this->domDocument);
        $result = $xpath->query($query);
        if($result->length){
          // simply remove the child from the documents
          // documentElement
          $this->domDocument->documentElement->removeChild($result->item(0));

          // save back to disk
          $this->domDocument->saveXML();
          $this->domDocument->save($this->xmlPath);
          $result = true;
        }
        return $result;
      }

      public function editEmployee($oldName, $name, $gender, $phone, $email) {
        $result = false;
        // edit employee
        // get the employee element based on name
        $query = '//employees/employee/name[text() = "' . $oldName . '"]/..';
        $xpath = new DOMXPath($this->domDocument);
        $result = $xpath->query($query);
        //print_r($query);
        //print_r($result);
        if($result->length){
          $result->item(0)->getElementsByTagName('name')->item(0)->nodeValue = $name;
          $result->item(0)->getElementsByTagName('gender')->item(0)->nodeValue = $gender;
          $result->item(0)->getElementsByTagName('phone')->item(0)->nodeValue = $phone;
          $result->item(0)->getElementsByTagName('email')->item(0)->nodeValue = $email;
          // save back to disk
          $this->domDocument->saveXML();
          $this->domDocument->save($this->xmlPath);
          $result = true;
        }

        return $result;
      }

  }
  $path = "employees.xml";
  $emp = new Contact($path);
  /*$arr = $emp->getThemAll();
  var_dump($arr);
  echo "<br><hr>";*/

  /*$arr = $emp->findEmployeeByName("Maria");
  var_dump($arr);
  echo "<br><hr>";*/

  /*$arr = $emp->findEmployeeByEmail("khogaeslam@gmail.com");
  var_dump($arr);
  echo "<br><hr>";*/

  /*$emp->addEmployee("Maimona","ale","02222222222","Maimona@khoga.com");
  $arr = $emp->getThemAll();
  var_dump($arr);
  echo "<br><hr>";*/

  /*$emp->deleteEmployee("Maimona");
  $arr = $emp->getThemAll();
  var_dump($arr);
  echo "<br><hr>";*/

  /*$emp->editEmployee("Khoga", "Eslam", "Male", "01220051999", "KhogaEslam@Yahoo.com");
  $arr = $emp->getThemAll();
  var_dump($arr);
  echo "<br><hr>";*/

  $arr = $emp->getThemAll();
  $nameErr = $emailErr = "";
  $name = $email = $gender = $phone = "";

  if($_SERVER['REQUEST_METHOD'] == 'POST')
    $counter = $_POST['counter'];
  else {
    $counter = 0;
  }
  $arrayCount = count($arr);
  if($arrayCount>0){
    $message = "Number of Employees = ".$arrayCount;
    $name = $arr[$counter]["name"];
    $email = $arr[$counter]["email"];
    $gender = $arr[$counter]["gender"];
    $phone = $arr[$counter]["phone"];
  }
  else{
    $message = "No Data Available";
  }

  function find_key_value($array, $key, $val)
  {
      foreach ($array as $item)
      {
          if (is_array($item) && find_key_value($item, $key, $val)) return true;

          if (isset($item[$key]) && $item[$key] == $val) return true;
      }

      return false;
  }
  function nextEmp(){
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    if($counter < $arrayCount-1){
      $counter+=1;
      $name = $arr[$counter]["name"];
      $email = $arr[$counter]["email"];
      $gender = $arr[$counter]["gender"];
      $phone = $arr[$counter]["phone"];
    }
    else{
      $counter=0;
      $name = $arr[$counter]["name"];
      $email = $arr[$counter]["email"];
      $gender = $arr[$counter]["gender"];
      $phone = $arr[$counter]["phone"];
    }
  }
  function prevEmp(){
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    if($counter > 0){
      $counter-=1;
      $name = $arr[$counter]["name"];
      $email = $arr[$counter]["email"];
      $gender = $arr[$counter]["gender"];
      $phone = $arr[$counter]["phone"];
    }
    else{
      $counter=$arrayCount-1;
      $name = $arr[$counter]["name"];
      $email = $arr[$counter]["email"];
      $gender = $arr[$counter]["gender"];
      $phone = $arr[$counter]["phone"];
    }
  }
  function clearEmp(){
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    $name = "";
    $email = "";
    $gender = "";
    $phone = "";
  }
  function insertEmp(){
    //echo "Hello insert";
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    //print_r($_POST);
    extract($_POST);
    if(find_key_value($arr,"name",$name))
    {
      $nameErr = "Name Already Exists!";
      return;
    }
    if(find_key_value($arr,"email",$email))
    {
      $emailErr = "Email Already Exists!";
      return;
    }

    $emp->addEmployee($name, $gender, $phone, $email);
    $arr = $emp->getThemAll();
    $arrayCount = count($arr);
    $counter = $arrayCount-1;

  }
  function updateEmp(){
    //echo "Hello update";
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    $prevName = $arr[$counter]["name"];
    extract($_POST);
    $emp->editEmployee($prevName, $name, $gender, $phone, $email);
  }
  function deleteEmp(){
    //echo "Hello delete";
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    $name = $arr[$counter]["name"];
    $emp->deleteEmployee($name);
    $arr = $emp->getThemAll();
    $arrayCount = count($arr);
    $counter = $arrayCount-1;
  }
  function searchEmp(){
    //echo "Hello search";
    global $emp, $arr, $counter, $arrayCount, $name, $email, $gender, $phone, $nameErr, $emailErr;
    $name = $_POST["name"];
    $email = $_POST["email"];
    $resultArr = array();
    if($name !== ""){
      $resultArr = $emp->findEmployeeByName($name);
      //print_r($resultArr);
      if(count($resultArr)>0){
        //print_r($resultArr);
        $name = $resultArr[0]["name"];
        $email = $resultArr[0]["email"];
        $gender = $resultArr[0]["gender"];
        $phone = $resultArr[0]["phone"];
      }
    }
    elseif($email !== ""){
      $resultArr = $emp->findEmployeeByEmail($email);
      //print_r($resultArr);
      if(count($resultArr)>0){
        //print_r($resultArr);
        $name = $resultArr[0]["name"];
        $email = $resultArr[0]["email"];
        $gender = $resultArr[0]["gender"];
        $phone = $resultArr[0]["phone"];
      }
    }
    else{
      $name = "No Data Found!";
      $email = "";
      $gender = "";
      $phone = "";
    }
  }

  function test(){
    global $arr, $counter, $arrayCount;
    echo $counter."Hello Test!<br>";
    print_r($arr[$counter]);
  }

  if (isset($_POST["next"])){
    nextEmp();
  }
  if (isset($_POST["prev"])){
    prevEmp();
  }
  if (isset($_POST["cancel"])){
    clearEmp();
  }
  if (isset($_POST["insert"])){
    insertEmp();
  }
  if (isset($_POST["update"])){
    updateEmp();
  }
  if (isset($_POST["delete"])){
    deleteEmp();
  }
  if (isset($_POST["search"])){
    searchEmp();
  }
?>

            <form id="form_15886" class="appnitro"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="form_description">
                <h2>Employee Details</h2>
                <p>Employee's Details</p>
                <center>
                  <p><?php echo htmlspecialchars((isset($message))?$message:'');?></p>
                </center>
              </div>
            			<ul>

            				<li id="li_1" >
            					<label class="description" for="name">Name </label>
            					<div>
            						<input id="name" name="name" class="element text medium" type="text" maxlength="255"  value="<?php echo htmlspecialchars((isset($name))?$name:'');?>"/>
            					</div>
            					<p class="guidelines" id="guide_1">
                        <small>Employee's Name</small>
                      </p>
                      <span class="error">* <?php echo $nameErr;?></span>
            				</li>

                    <li id="li_4" >
            				<label class="description" for="gender">Gender </label>
            				<div>
            					<select class="element select medium" id="gender" name="gender">
            						<option <?php if ($gender == "" ) echo 'selected' ; ?> value="" ></option>
            						<option <?php if ($gender == "male" || $gender == "Male") echo 'selected' ; ?> value="male" >Male</option>
            						<option <?php if ($gender == "female" || $gender == "Female") echo 'selected' ; ?> value="female" >Female</option>
            						<option <?php if ($gender == "other" || $gender == "Other") echo 'selected' ; ?> value="other" >Other</option>
            					</select>
            				</div>
            				<p class="guidelines" id="guide_4"><small>Employee's Gender</small></p>
            				</li>

            				<li id="li_2" >
            					<label class="description" for="phone">Phone </label>
            					<div>
            						<input id="phone" name="phone" class="element text medium" type="text" maxlength="255"  value="<?php echo htmlspecialchars((isset($phone))?$phone:'');?>"/>
            					</div>
            					<p class="guidelines" id="guide_2"><small>Employee's Phone</small></p>
            				</li>

            				<li id="li_3" >
              				<label class="description" for="email">Email </label>
              				<div>
              					<input id="email" name="email" class="element text medium" type="text" maxlength="255"  value="<?php echo htmlspecialchars((isset($email))?$email:'');?>"/>
              				</div>
                      <p class="guidelines" id="guide_3"><small>Employee's Email</small></p>
                      <span class="error">* <?php echo $emailErr;?></span>
            				</li>
                    <li>
                      <input type="hidden" name="counter" value="<?php echo htmlspecialchars((isset($counter))?$counter:0); ?>" />
                    </li>
                    <li id="li_4" class="buttons">
                        <button name="prev" type="submit" value="submit" >prev</button>
              					<button name="next" type="submit" value="submit" >next</button>
            				</li>

            				<li id="li_5" class="buttons">
            					<button name="insert" id="saveForm1" class="button_text" type="submit" value="submit"  >Insert</button>
            					<button name="update" id="saveForm2" class="button_text" type="submit" value="submit"  >Update</button>
            					<button name="delete" id="saveForm3" class="button_text" type="submit" value="submit"  >Delete</button>
            					<button name="search" id="saveForm4" class="button_text" type="submit" value="submit"  >Search</button>
                      <button name="cancel" id="saveForm5" class="button_text" type="submit" value="submit"  >Clear</button>
            				</li>
            			</ul>
            		</form>
            	</div>
            	<img id="bottom" src="bottom.png" alt="">
          	</body>
        </html>
