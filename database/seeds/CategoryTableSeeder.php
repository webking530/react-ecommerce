<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
DB::table('categories')->delete();
DB::table('categories')->insert(array(
  array('id' => '1','title' => 'Mens & Womens','parent_id' => '0','image_name' => 'ssnp3iwq0bjilstatl1c','icon_name' => 'jyohjyecku5ocqc7dzcc','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-02-03 18:58:46'),
  array('id' => '2','title' => 'Kids','parent_id' => '0','image_name' => 'fs6tkvuzhdealdmiykf6','icon_name' => 'jujfasr6g2xqv42jsjyt','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-02-03 18:58:33'),
  array('id' => '3','title' => 'Pets','parent_id' => '0','image_name' => 'yuh0ut037v7zajthrf18','icon_name' => 'bgvvrzhdxdivwh94ybei','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-01-31 18:53:51'),
  array('id' => '4','title' => 'Home','parent_id' => '0','image_name' => 'iwqz6ymvvtwenui4v2qi','icon_name' => 'yprm9lev2byo6hee1wsv','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-02-03 15:48:23'),
  array('id' => '5','title' => 'Gadgets','parent_id' => '0','image_name' => 'jxskdztu50gq9soq8vk2','icon_name' => 'iegdtkgv7dx45pbqepso','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-01-31 18:55:07'),
  array('id' => '6','title' => 'Art','parent_id' => '0','image_name' => 'rsvh5sptqo9vmeqsysmp','icon_name' => 'atgxexcxw2xh4znhtslm','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-02-04 15:18:55'),
  array('id' => '7','title' => 'Food','parent_id' => '0','image_name' => 'g7phus7azcldqefdsedr','icon_name' => 'od1cpkcukmrlgfdjsbos','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-02-04 15:19:21'),
  array('id' => '8','title' => 'Media','parent_id' => '0','image_name' => 'jfvdyepxdr5ozs1o5kbm','icon_name' => 'ofkbi5tyop73o61gfmxv','status' => 'Active','featured' => 'Yes','browse' => 'Yes','created_at' => NULL,'updated_at' => '2020-02-04 16:01:09'),
  array('id' => '9','title' => 'Architecture','parent_id' => '0','image_name' => 'nhfae05aepdj1px87dgr','icon_name' => 'xzairdopdm25sb6ogwiu','status' => 'Inactive','featured' => 'No','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 18:32:41'),
  array('id' => '10','title' => 'Travel & destination','parent_id' => '0','image_name' => 'yjzv9zevey1dh0phd59j','icon_name' => 'cjdiowfzji6ybmtry6mp','status' => 'Inactive','featured' => 'No','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 18:32:48'),
  array('id' => '11','title' => 'Sports & outdoors','parent_id' => '0','image_name' => 'eep6jbvzaypgsnszy2wv','icon_name' => 'w0filjftbn2oft0qfpdh','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-01-31 19:04:09'),
  array('id' => '12','title' => 'DIY & Crafts','parent_id' => '0','image_name' => 'rryjmfsnlq2djaf4dvo3','icon_name' => 'l8np2nacg7tqhyrgft4x','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-04 15:19:46'),
  array('id' => '13','title' => 'Workspace','parent_id' => '0','image_name' => 'jevrqd6v7ajal7a52tba','icon_name' => 'bponcwtsbkizjyxx2l9w','status' => 'Inactive','featured' => 'No','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 18:33:02'),
  array('id' => '14','title' => 'Car & Vehicles','parent_id' => '0','image_name' => 'y99eq6b31kziv9feptej','icon_name' => 'nfj58k4qp7ktfyscnrwj','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-04 15:19:57'),
  array('id' => '15','title' => 'Clothing','parent_id' => '1','image_name' => 'rrmkk4uwz5zraqvhsy0z','icon_name' => 'yx2mkqe93tc8irk9bbai','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:13:52'),
  array('id' => '16','title' => 'Shoes','parent_id' => '1','image_name' => 'qcxsj8t93eevfoiyjktb','icon_name' => 'fmfff978mgetdoeuxvma','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:14:37'),
  array('id' => '17','title' => 'Accessories','parent_id' => '1','image_name' => 'lpuciicydgvpsxtj4pco','icon_name' => 'lkkcjeyzxmerbfumnjdv','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:15:15'),
  array('id' => '18','title' => 'Tops','parent_id' => '15','image_name' => 'bssvmtaor68rscwtfieh','icon_name' => 'nnmyyevcdgpafzihszlc','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:15:49'),
  array('id' => '19','title' => 'Bottoms','parent_id' => '15','image_name' => 'anm57zrgattilt6diplv','icon_name' => 'kekrzfolt5ijdxif8dur','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:16:28'),
  array('id' => '20','title' => 'Outerwear','parent_id' => '15','image_name' => 'fk7qa1wpphuturl4jyas','icon_name' => 'prafcclpaa8dy7xqvb8q','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-04 10:10:19'),
  array('id' => '21','title' => 'Toys','parent_id' => '2','image_name' => 'o6qozuvgmvs97tiqyyip','icon_name' => 'uxhgvj1pkyksnz7klxhq','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:18:59'),
  array('id' => '22','title' => 'Games','parent_id' => '2','image_name' => 'zz0nw2drlesmbyau7ety','icon_name' => 'hqws0aky8ryulsjj22tq','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:19:17'),
  array('id' => '23','title' => 'Kitchen','parent_id' => '4','image_name' => 'fy4xoernatf1zd0jtp7b','icon_name' => 'v0rubi3v1f2nxfapr615','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:19:37'),
  array('id' => '24','title' => 'Bedding','parent_id' => '4','image_name' => 'df4apqzztaljv5bnkjfr','icon_name' => 'jcmayfqeks49d70e5nnb','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:20:25'),
  array('id' => '25','title' => 'Coffee & tea','parent_id' => '23','image_name' => 'bpedadzis7lodxkns0su','icon_name' => 's3swhbeuvf8jnttespur','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:21:37'),
  array('id' => '26','title' => 'Appliance','parent_id' => '23','image_name' => 'qrqhl7ktifbein7uapj3','icon_name' => 'pdbv3htclcojppgettwz','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:22:24'),
  array('id' => '27','title' => 'Audio','parent_id' => '5','image_name' => 'ulirpdagyawjbfqbdz6w','icon_name' => 'onn8ijxos4axv1wsebq5','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:23:01'),
  array('id' => '28','title' => 'Cameras','parent_id' => '5','image_name' => 'zxyhixeajsomtyc7rqxo','icon_name' => 'zls4xayv4stgt1ob2vws','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:23:15'),
  array('id' => '29','title' => 'Headphones','parent_id' => '27','image_name' => 'i0oipp7s0uejbtpkxpsy','icon_name' => 'jlivtehhxtiz7aohylsl','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:24:15'),
  array('id' => '30','title' => 'Speaker','parent_id' => '27','image_name' => 'psxdkzaj4bdznh3nlowv','icon_name' => 'n4uuo99x9swvit0cscfe','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:24:38'),
  array('id' => '31','title' => 'Analog','parent_id' => '28','image_name' => 'b3dvgvecd8ljwpm31v24','icon_name' => 'ixjrlvuwnztqap4uptox','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:27:22'),
  array('id' => '32','title' => 'Digital','parent_id' => '28','image_name' => 'uiy4ljwbgbezrkolr9n4','icon_name' => 'idpcu43rcyickryc7cqt','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => NULL,'updated_at' => '2020-02-03 15:27:51'),
  array('id' => '33','title' => 'Rugs','parent_id' => '4','image_name' => 'zp1lvgbdotzevx1fumfs','icon_name' => 'kqxt5rcfc5hc0myzjy9s','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => '2020-02-03 12:01:59','updated_at' => '2020-02-03 15:28:38'),
  array('id' => '34','title' => 'Modern Contemporary Rugs','parent_id' => '33','image_name' => 'xuahy8j0fmvaujnylcux','icon_name' => 'y5ybg502r25bcwwcgqyh','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => '2020-02-03 12:16:12','updated_at' => '2020-02-03 15:29:40'),
  array('id' => '35','title' => 'Jewelry','parent_id' => '1','image_name' => 'mx6rwoi1yofjlftxnkxx','icon_name' => 'hzdp7yqkfbk3gx3rodx5','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => '2020-02-03 18:54:46','updated_at' => '2020-02-03 19:03:31'),
  array('id' => '36','title' => 'Dresses','parent_id' => '1','image_name' => 'pvs33a8wyuz2c7d3azif','icon_name' => 'fgo1s3mcjbny7skzcxq6','status' => 'Active','featured' => 'Yes','browse' => 'No','created_at' => '2020-02-03 18:56:04','updated_at' => '2020-02-03 19:04:48')
));
}
}
