<?php
function identifyOnlyOneNumber($input) {
    $number = 0;

    // 统计字符串中包含的数字字符个数
    preg_match_all('/[一二三四五六七八九1-9]/u', $input, $matches);
    $count = count($matches[0]);

    // 判断数字字符个数是否为1，并根据字符内容进行映射
    if ($count === 1) {
        $char = $matches[0][0];
        switch ($char) {
            case '一':
			case '七':
			case '1':
            case '7':
                $number = 7;
                break;
            case '二':
			case '八':
            case '2':
            case '8':
                $number = 8;
                break;
            case '三':
            case '九':
            case '3':
            case '9':
                $number = 9;
                break;
            default:
                $number = 0;
                break;
        }
    }

    return $number;
}
function identifyChGrade($input) {
    $chineseNumberMap = [
        '一' => 7,
        '二' => 8,
        '三' => 9,
    ];

    if (strpos($input, '國一') !== false) {
        return $chineseNumberMap['一'];
    } elseif (strpos($input, '國二') !== false) {
        return $chineseNumberMap['二'];
    } elseif (strpos($input, '國三') !== false) {
        return $chineseNumberMap['三'];
    }

    return 0; 
}
function isChineseNumberConvertible($input) {
    $chineseNumberMap = [
        '零' => '0',
        '一' => '1',
        '二' => '2',
        '三' => '3',
        '四' => '4',
        '五' => '5',
        '六' => '6',
        '七' => '7',
        '八' => '8',
        '九' => '9',
    ];

    $numberString = '';
    $length = mb_strlen($input, 'UTF-8');
    for ($i = 0; $i < $length; $i++) {
        $ch = mb_substr($input, $i, 1, 'UTF-8');
        if (isset($chineseNumberMap[$ch])) {
            $numberString .= $chineseNumberMap[$ch];
        } else {
            return 0; // 如果遇到非中文数字字符，则返回 0
        }
    }

    return $numberString;
}
function identifyGrade($input) {
    $grade = 0;
	if ($grade === 0) {
		// 包含連續三個數字的直接取出來
		preg_match('/\d{3}/', $input, $matches);
		if (!empty($matches)) {
			$firstDigit = intval(substr($matches[0], 0, 1));
			if ($firstDigit === 1 || $firstDigit === 7) {
				$grade = 7;
			} elseif ($firstDigit === 2 || $firstDigit === 8) {
				$grade = 8;
			} elseif ($firstDigit === 3 || $firstDigit === 9) {
				$grade = 9;
			}
		}
	}

	if ($grade === 0) {
		
		if(isChineseNumberConvertible($input)!=0){
			//三一三 若可以全部轉成數字就轉成數字後跑一次這個函式
			$grade = identifyGrade(isChineseNumberConvertible($input));
			
		}else if(identifyChGrade($input)!=0){
			//國一、國二、國三
			$grade = identifyChGrade($input);
			
		}else if(identifyOnlyOneNumber($input)!=0){
			//字串中只有一個(數字{中文或英文})字元，判斷出來後就給出這個數字的數值型態，若數字落在(1,2,3,7,8,9)則依照對應年級給出(7,8,9)，否則為0
			$grade = identifyOnlyOneNumber($input);
		}else{		
			////一忠、二孝，七愛
			//$chineseNumberMap = [
			//	'一' => 7,
			//	'二' => 8,
			//	'三' => 9,
			//	'七' => 7,
			//	'八' => 8,
			//	'九' => 9,
			//];
			//
			//foreach ($chineseNumberMap as $chineseNumber => $number) {
			//	if (mb_strpos($input, $chineseNumber) !== false) {
			//		$grade = $number;
			//		break;
			//	}
			//}		
		}			
	}   

    return $grade;
}

// 测试
$inputs = ["902B", "104", "203", "801", "三一三", "802三", "三忠", "一義", "一孝", "七孝", "八甲", "B902","資優班","7ii"];
foreach ($inputs as $input) {
    $output = identifyGrade($input);
    echo "$input --> $output<br>" . PHP_EOL;
}
?>