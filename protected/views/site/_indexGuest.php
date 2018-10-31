<div class="md-card">
    <div class="md-card-content">
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-1-2">
                <pre class="line-numbers">
                    <code class="language-php">
$arr = [
    [
        1, 37, 8, 9, 140, 217
    ],
    [
        21, 75, 38, 97, 10, 17
    ],
    [
        31, 76, 8, 49, 10, 147
    ],
    [
        1, 76, 83, 9, 180, 137
    ],
];
print_r($arr);

$temp = [];
foreach ($arr as $subArray) {
    $max1 = max($subArray);
    $key=array_search($max1, $subArray);
    unset($subArray[$key]);
    $max2 = max($subArray);
    $temp[] = $max1 + $max2;
}
$maxSumm = max($temp);

$key = array_search($maxSumm, $temp);

print_r($arr[$key]);
                    </code>
                </pre>
            </div>

            <div class="uk-width-large-1-2">
                <pre class="line-numbers">
                    <code class="language-php">
<?php
$arr = [
    [
        1, 37, 8, 9, 140, 217
    ],
    [
        21, 75, 38, 97, 10, 17
    ],
    [
        31, 76, 8, 49, 10, 147
    ],
    [
        1, 76, 83, 9, 180, 137
    ],
];
print_r($arr);

$temp = [];
foreach ($arr as $subArray) {
    $max1 = max($subArray);
    $key=array_search($max1, $subArray);
    unset($subArray[$key]);
    $max2 = max($subArray);
    $temp[] = $max1 + $max2;
}
$maxSumm = max($temp);

$key = array_search($maxSumm, $temp);
?>
Максимальная сумма 2х элементов в массиве $arr[<?=$key?>] <br>
<?php
print_r($arr[$key]);
?>
                    </code>
                </pre>
            </div>
        </div>
    </div>
</div>

<div class="md-card">
    <div class="md-card-content">
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-1-2">
                <pre class="line-numbers">
                    <code class="language-php">
CREATE TABLE user (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name string 255,
    second_name string 255,
    email string 255
);

CREATE TABLE orders (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
userid string 255,
product_id string 255,
cost string 255
);

SELECT
	user.*,
    sum(orders.cost) as summ,
FROM
	user
LEFT JOIN
	orders
ON
	orders.userid = user.id
GROUP BY
	user.id;


SELECT
	user.*,
    sum(orders.cost) as summ,
	COUNT(orders.id) as count
FROM
	user
INNER JOIN
	orders
ON
	orders.userid = user.id
GROUP BY
	user.id;
                    </code>
                </pre>
            </div>

            <div class="uk-width-large-1-2">
                <pre class="line-numbers">
                    <code class="language-php">

/* `123`.`user` */
$user = array(
array('id' => '1','name' => 'user1','second_name' => 'user','email' => 'userovich'),
array('id' => '2','name' => 'user2','second_name' => 'user','email' => 'userovich'),
array('id' => '3','name' => 'user3','second_name' => 'user','email' => 'userovich')
);

/* `123`.`orders` */
$orders = array(
    array('id' => '1','userid' => '1','product_id' => '2','cost' => '1'),
    array('id' => '2','userid' => '1','product_id' => '5','cost' => '1'),
    array('id' => '4','userid' => '1','product_id' => '4','cost' => '1'),
    array('id' => '5','userid' => '2','product_id' => '47','cost' => '1'),
    array('id' => '9','userid' => '2','product_id' => '4','cost' => '1'),
    array('id' => '10','userid' => '2','product_id' => '1','cost' => '1'),
    array('id' => '11','userid' => '2','product_id' => '45','cost' => '1'),
    array('id' => '12','userid' => '2','product_id' => '78','cost' => '1'),
    array('id' => '13','userid' => '2','product_id' => '46','cost' => '2')
);

// Left join
$orders = array(
    array('id' => '1','name' => 'user1','second_name' => 'user','email' => 'userovich','summ' => '3'),
    array('id' => '2','name' => 'user2','second_name' => 'user','email' => 'userovich','summ' => '7'),
    array('id' => '3','name' => 'user3','second_name' => 'user','email' => 'userovich','summ' => NULL)
);

// inner join
/* `123`.`user` */
$user = array(
    array('id' => '1','name' => 'user1','second_name' => 'user','email' => 'userovich','summ' => '3','count' => '3'),
    array('id' => '2','name' => 'user2','second_name' => 'user','email' => 'userovich','summ' => '7','count' => '6')
);


                    </code>
                </pre>
            </div>
        </div>
    </div>
</div>