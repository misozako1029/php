<?php
$db_columns = [
	"recruitment_year",
	"job_offer_id",
	"reception_center",
	"office_number",
	"is_public_internet",
	"company_name_kana",
	"company_name",
	"postal_code",
	"address",
	"access_info",
	"representative_name",
	"corporate_number",
	"website_url",
	"employee_count_total",
	"employee_count_office",
	"established_date",
	"capital_stock",
	"business_description",
	"company_features",
	"employment_type",
	"job_category",
	"job_description",
	"work_postal_code",
	"work_address",
	"work_access",
	"vacancies_commute",
	"vacancies_live_in",
	"vacancies_any",
	"required_skills",
	"transfer_possibility",
	"housing_single",
	"housing_family",
	"basic_salary",
	"monthly_salary_total",
	"bonus_new_grad",
	"holidays",
	"annual_holidays",
	"multi_application_acceptance",
	"multi_application_start_date",
	"workplace_visit_acceptance",
	"workplace_visit_schedule",
	"contact_department_role",
	"contact_name",
	"contact_name_kana",
	"contact_phone",
	"contact_fax",
	"contact_email",
	"supplementary_notes",
	"special_notes",
	"industry_classification",
	"occupation_class_3digits",
	"occupation_class_5digits",
	"occupation_class2_3digits",
	"occupation_class2_5digits",
	"work_city",
	"work_city2",
	"work_city3",
	"management_number",
	"target_department",
	"area_name",
	"graduate_hiring_record",
	"other_classification",
	"recruitment_count",
	"school_visit",
	"secondary_recruitment",
	"visit_count",
	"applicant_count",
	"hiring_count",
	"remarks",
	"handover_notes",
	"publish_datetime",
	"last_updated_datetime",
	"registration_method",
	"original_filename",
	"hw_occ_class_large",
	"hw_occ_class_middle",
	"hw_occ_class_small",
	"handy_occ_class_large",
	"handy_occ_class_middle",
	"hw_occ_class2_large",
	"hw_occ_class2_middle",
	"hw_occ_class2_small",
	"handy_occ_class2_large",
	"handy_occ_class2_middle",
	"favorite_count",
];

$check_num = [

	"recruitment_year",
	"employee_count_total",
	"employee_count_office",
	"vacancies_commute",
	"vacancies_live_in",
	"vacancies_any",
	"basic_salary",
	"monthly_salary_total",
	"annual_holidays",
	"recruitment_count",
	"visit_count",
	"applicant_count",
	"hiring_count",
	"favorite_count",
];

$check_null = [
	"recruitment_year",
	"job_offer_id",
	"reception_center",
	"office_number",
	"is_public_internet",
	"company_name_kana",
	"company_name",
	"postal_code",
	"address",
	// "access_info",
	"representative_name",
	"corporate_number",
	"website_url",
	"employee_count_total",
	"employee_count_office",
	"established_date",
	"capital_stock",
	"business_description",
	"company_features",
	"employment_type",
	"job_category",
	"job_description",
	"work_postal_code",
	"work_address",
	// "work_access",
	"vacancies_commute",
	// "vacancies_live_in",
	"vacancies_any",
	"required_skills",
	"transfer_possibility",
	// "housing_single",
	// "housing_family",
	"basic_salary",
	"monthly_salary_total",
	"bonus_new_grad",
	"holidays",
	"annual_holidays",
	// "multi_application_acceptance",
	// "multi_application_start_date",
	"workplace_visit_acceptance",
	"workplace_visit_schedule",
	"contact_department_role",
	"contact_name",
	// "contact_name_kana",
	"contact_phone",
	// "contact_fax",
	// "contact_email",
	"supplementary_notes",
	"special_notes",
	"industry_classification",
	// "occupation_class_3digits",
	// "occupation_class_5digits",
	"work_city",
	"management_number",
	"publish_datetime",
	"last_updated_datetime",
	"registration_method",
	"original_filename",
	// "hw_occ_class_large",
	// "hw_occ_class_middle",
	// "hw_occ_class_small",
	// "handy_occ_class_large",
	// "handy_occ_class_middle",
	"favorite_count"
];

$file_name = $_FILES['csv_file']['name'];
$file_tempname = $_FILES['csv_file']['tmp_name'];

$filepath = 'C:/Users/60708/xampp/htdocs/php/csv-uploader/csv/' .$file_name;

move_uploaded_file($file_tempname, $filepath);

    try{
        $db = new PDO('mysql:dbname=csv_uploader;host=127.0.0.1;charset=utf8', 'root', '');
		if($_SERVER["REQUEST_METHOD"] == "POST") {  
            $f  = fopen("C:/Users/60708/xampp/htdocs/php/csv-uploader/csv/$file_name" , "r");
			$db -> beginTransaction();
			$header = 0;
			$Error_ms = [];
			$column_line = 1;
            while($line = fgetcsv($f)){
				if($header == 0) {
					$header = 1;
					continue;
                }
				$sql_columns = "";
				$sql_values = "";				
				$column_number = 0;
				foreach($db_columns as $column){
						$sql_columns = $sql_columns . $column . "," ;
						$sql_values =  $sql_values . " ?,";
						if($_POST['er_check'] == 1){
							if(in_array($column, $check_num)){
								if(!is_numeric($line[$column_number])){
									$Error_ms[] =  $column_line . '行目の' . $column. 'のデータが不正です' .'<br>';
								}
							}
							if(in_array($column,$check_null)){
								if($line[$column_number] == ""){
										$Error_ms[] = $column_line . '行目の' . $column . 'のデータが不正です' . '<br>';
								}
							}
						}
						$column_number++;
				}


				$sql_columns = substr($sql_columns, 0,strlen($sql_columns)-1);
				$sql_values = substr($sql_values, 0,strlen($sql_values)-1);
				foreach($line as $lines){
						$lines = "'" . $lines . "'" . ",";
				}
                $sql = "insert into job_offers (${sql_columns})values(${sql_values});" ;
				$pre = $db -> prepare($sql);

				$pre -> execute($line);

				
				$column_line++;

            }
			if(!empty($Error_ms)){
				$comment = <<< EOT
					<h2>データの登録に失敗しました</h2>
					<a href="index.html">ホームに戻る</a>
					<br>
				EOT;
				print $comment;

				foreach($Error_ms as $ms){
					print $ms;
				}
				$db ->rollBack();
			}else{

				$db -> commit();
				print '<h2>'."データの登録に成功しました" . '</h2>';
				print '<a href="index.html">ホームに戻る</a>';

			}


            fclose($f);
        }
	} catch(PDOException $e) {
		print 'DB接続エラー：' . $e->getMessage();
		$db -> rollBack();
	}

?>