
server="cd01"
job=
command_str=
while getopts "s:j:c:" opt; do
  case $opt in
    s)
      echo "-s was server key: $OPTARG" >&2
      server=$OPTARG
      ;;
    j)
      echo "-j was job name:  $OPTARG" >&2
      job=$OPTARG
      ;;
    c)
      echo "-c was command_str:  $OPTARG" >&2
      command_str=$OPTARG
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      exit 1
      ;;
  esac
done

if [ -z "$job" ]; then 
    echo "Option job requires an argument." 
    exit 1
fi
echo $job
echo $server
curl --location --request POST 'http://47.108.88.145/create' --form "job_key=$job" --form "server=$server"
echo -e "\n"

$command_str

if [ $? -eq 0 ]; then 
    status='done'
else
    status='fail'
fi

echo -e "\n"
curl --location --request POST 'http://47.108.88.145/done' --form "job_key=$job" --form "server=$server"  --form "status=$status"