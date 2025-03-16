read -a number
sum=0
for ((i = 1; i <= ${#number[@]}; i++)); do
sum=$((sum + number[i]))
done
average=$(echo "scale=3; $sum / ${number[0]}" | bc)
echo $average