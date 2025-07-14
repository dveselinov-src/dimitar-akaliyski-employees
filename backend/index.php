<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require 'vendor/autoload.php';

use League\Csv\Reader;

//https://csv.thephpleague.com/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //is there a file
    if (!isset($_FILES['emp_coop_file'])) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded, or corrupt file!']);
        exit;
    }

    //is the file a valid csv
    $file = $_FILES['emp_coop_file'];
    if ($file['type'] !== 'text/csv') {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file format, or corrupt file! Please upload a valid CSV file!']);
        exit;
    }

    try {
        $csv = Reader::createFromPath($file['tmp_name']);
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $requiredHeaders = ['EmpID', 'ProjectID', 'DateFrom', 'DateTo'];
        //is the file in the correct format
        if (!array_diff($requiredHeaders, $csv->getHeader())) {
            $data = [];
            foreach ($records as $record) {
                //is the data of the correct type
                if (!is_numeric($record['EmpID']) || !is_numeric($record['ProjectID']) || !strtotime($record['DateFrom'])
                    || ($record['DateTo'] !== '' && $record['DateTo'] !== 'NULL' && !strtotime($record['DateTo']))
                ) {
                    throw new Exception('Invalid data format in CSV! Expected EmpID INT, ProjectID INT, DateFrom DATE, DateTo NULL or DATE');
                }
                $data[] = [
                    'EmpID' => (int)$record['EmpID'],
                    'ProjectID' => (int)$record['ProjectID'],
                    'DateFrom' => $record['DateFrom'],
                    'DateTo' => $record['DateTo'] === 'NULL' || $record['DateTo'] === '' ? date('Y-m-d') : $record['DateTo'],
                ];
            }

            //days worked together
            $employeeProjects = [];
            foreach ($data as $row) {
                $employeeProjects[$row['EmpID']][$row['ProjectID']][] = [
                    'DateFrom' => new DateTime($row['DateFrom']),   //should work for all common formats
                    'DateTo' => new DateTime($row['DateTo']),
                ];
            }
            $employeeProjectsC = $employeeProjects;
            $pairCoopDays = [];
            //var_dump($employeeProjects);
            foreach ($employeeProjects as $emp1 => $projects1) {
                foreach ($employeeProjectsC as $emp2 => $projects2) {
                    //if same emp, move on
                    if ($emp1 >= $emp2)
                        continue;

                    foreach ($projects1 as $projectId => $dates1) {
                        //if second emp hasn't worked on this project, move on
                        if (!isset($projects2[$projectId]))
                            continue;

                        foreach ($dates1 as $date1) {
                            foreach ($projects2[$projectId] as $date2) {
                                //from the date of the second person joining the project, to the date of the first person leaving it
                                $start = max($date1['DateFrom'], $date2['DateFrom']);
                                $end = min($date1['DateTo'], $date2['DateTo']);
                                //var_dump($emp1, $emp2, $projectId, $start, $end);
                                if ($start <= $end) {
                                    $days = $start->diff($end)->days + 1;   //+1 to include the end day

                                    $pairKey = min($emp1, $emp2) . '-' . max($emp1, $emp2);
                                    if (!isset($pairCoopDays[$pairKey])) {
                                        $pairCoopDays[$pairKey] = [
                                            'emp1' => min($emp1, $emp2),
                                            'emp2' => max($emp1, $emp2),
                                            'days' => 0
                                        ];
                                    }
                                    $pairCoopDays[$pairKey]['days'] += $days;
                                    $pairCoopDays[$pairKey]['commonProjects'][] = [$emp1, $emp2, $projectId, $days];
                                }
                            }
                        }
                    }
                }
            }
            //var_dump($pairCoopDays);
            $maxDays = 0;
            $result = null;
            foreach ($pairCoopDays as $pair) {
                if ($pair['days'] > $maxDays) {
                    $maxDays = $pair['days'];
                    $result = $pair;
                }
            }
            if (!$result) {
                http_response_code(400);
                echo json_encode(['error' => 'Data does not include pairs!']);
                exit;
            }
            //var_dump($result);
            echo json_encode(['result' => $result]);

        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid CSV headers! Expected format: EmpID, ProjectID, DateFrom, DateTo']);
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
}