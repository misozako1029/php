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
			$header = 0;
            while($line = fgetcsv($f)){
				if($header == 0) {
					$header = 1;
					continue;
                }
				$db_columns[] = ',';
				array_unshift($line, "'");
				$line[] = "',";
                $sql = "insert into job_offers (:columns)values(:id);" ;
				$pre = $db -> prepare($sql);
				foreach($db_columns as $colum){
				$pre ->bindValue(':columns',$colum,PDO::PARAM_STR);
				}
				foreach($line as $lines){
				$pre ->bindValue(':id', $lines,PDO::PARAM_STR);
				}
				print_r ($pre);
				$pre -> execute();
            }



            fclose($f);
        }
	} catch(PDOException $e) {
		print 'DB接続エラー：' . $e->getMessage();
	}

?>