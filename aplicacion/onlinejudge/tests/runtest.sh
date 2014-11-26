# @package   local_online_uv_judge
# @author    Oscar Chamat
# @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

cd ../../../
for i in $(find local/onlinejudge/tests/ -name "*_test.php")
do
   echo $i
   vendor/bin/phpunit $i
   # or do whatever with individual element of the array
done