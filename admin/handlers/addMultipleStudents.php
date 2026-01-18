<?php
require_once __DIR__ . "/../../config/config.php";
header("Content-Type: application/json");

$response = ["status"=>"error","message"=>"Unexpected error occurred.","field"=>"general"];

try {
    $batch_id = $_POST['session'] ?? '';
    $section  = $_POST['section'] ?? '';
    $csv_file = $_FILES['student_csv'] ?? null;

    if (empty($batch_id)) throw new Exception("Please select a batch.",1);
    if (empty($section)) throw new Exception("Please select a section.",1);
    if (!$csv_file || $csv_file['size'] <= 0) throw new Exception("Please upload a CSV file.",1);

    $stmt = $connection->prepare("SELECT id FROM batch_sections WHERE batch_id=:batch AND section_name=:sec");
    $stmt->execute([':batch'=>$batch_id,':sec'=>$section]);
    $sectionRow = $stmt->fetch(PDO::FETCH_OBJ);
    if (!$sectionRow) throw new Exception("Selected section does not belong to selected batch.",1);
    $target_bs_id = $sectionRow->id;

    $stmt = $connection->prepare("SELECT COUNT(*) AS total FROM user WHERE batch_section_id=:bs_id");
    $stmt->execute([':bs_id'=>$target_bs_id]);
    $count = $stmt->fetch(PDO::FETCH_OBJ)->total;
    if ($count>0) throw new Exception("Section is not empty. $count students found.",1);

    $file = fopen($csv_file['tmp_name'],"r");
    $insertStmt = $connection->prepare("INSERT INTO user (username,email,registration_no,batch_section_id,status,survey_progress) VALUES (:name,:email,:reg,:bs_id,'active','pending')");

    $successCount=0;
    $errorRows=[];
    $csvEmails=[];
    $csvRegs=[];
    $rowIndex=0;

    while(($row=fgetcsv($file,10000,","))!==FALSE){
        $rowIndex++;
        if($rowIndex==1) continue;

        $name=trim($row[0]??'');
        $email=trim($row[1]??'');
        $reg=trim($row[2]??'');

        if(empty($name)||empty($email)) continue;

        if(in_array($email,$csvEmails)||in_array($reg,$csvRegs)){
            $errorRows[]=["name"=>$name,"email"=>$email,"reg_no"=>$reg,"message"=>"Duplicate found in CSV","field"=>"csvFileError"];
            continue;
        }
        $csvEmails[]=$email;
        $csvRegs[]=$reg;

        $stmt=$connection->prepare("SELECT id FROM user WHERE email=:email OR registration_no=:reg LIMIT 1");
        $stmt->execute([':email'=>$email,':reg'=>$reg]);
        if($stmt->fetch()){
            $errorRows[]=["name"=>$name,"email"=>$email,"reg_no"=>$reg,"message"=>"Email or Registration No already exists in system","field"=>"csvFileError"];
            continue;
        }

        try{
            $insertStmt->execute([':name'=>$name,':email'=>$email,':reg'=>$reg,':bs_id'=>$target_bs_id]);
            $successCount++;
        }catch(PDOException $e){
            $errorRows[]=["name"=>$name,"email"=>$email,"reg_no"=>$reg,"message"=>$e->getMessage(),"field"=>"csvFileError"];
        }
    }

    fclose($file);
    if(file_exists($csv_file['tmp_name'])) unlink($csv_file['tmp_name']);

    if($successCount===0 && !empty($errorRows)){
        $response=["status"=>"error","message"=>"Multiple records already exist in the system. No new students were added.","field"=>"csvFileError","errors"=>[]];
    }else{
        $response=["status"=>"success","message"=>"$successCount students added successfully.","errors"=>$errorRows];
    }

}catch(Exception $e){
    $response=["status"=>"error","message"=>$e->getMessage(),"field"=>"batch_section"];
}

echo json_encode($response);
exit;
