PROJECT_NAME=$1
TARGET_PATH=${2%/}

ABS_PATH="$(cd "$(dirname "$0")"; pwd)"

# check PROJECT_NAME and TARGET_PATH

# create project path; either absolute path or full path
if [ "${TARGET_PATH:0:1}" = "/" ]; then
    PROJECT_PATH=${TARGET_PATH}/${PROJECT_NAME}
else
    PROJECT_PATH=${ABS_PATH}/${TARGET_PATH}/${PROJECT_NAME}
fi

# check if target project is exists
if [ -d ${PROJECT_PATH} ] && [ "$(ls -A $PROJECT_PATH)" ]; then
    echo "Directory ${PROJECT_PATH} is not empty. Cannot overwrite it."
    echo "Terminated!"
    exit 1
elif [ -L {$PROJECT_PATH} ]; then
    echo "${PROJECT_PATH} is a symlink. Cannot overwrite it."
    echo "Terminated!"
    exit 1
fi

# create directory
mkdir -p ${PROJECT_PATH}
echo "Creating project at ${PROJECT_PATH}"

# copy to directory
cp -r ${ABS_PATH}/framework/* ${PROJECT_PATH}/
echo "Copied all framework files"

# prompt for variables

# ask to copy nginx config file
read -p "Would you like to create nginx config? " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Finished!"
    exit 0
fi

# prompt for nginx config
SERVER_NAME=${PROJECT_NAME}.local
NGINX_CONFIG_PATH=/etc/nginx/sites-available

read -p "SERVER_NAME (default: ${SERVER_NAME}): " input
echo
if [ ! -z ${input} ]; then
    SERVER_NAME=${input}
fi

echo "Creating nginx config at ${NGINX_CONFIG_PATH}/${PROJECT_NAME}.conf"
echo "SERVER_NAME: ${SERVER_NAME}"

# create nginx config file
sed "s#/var/www/goonjs-slim-eloquent/framework#${PROJECT_PATH}#g" ${ABS_PATH}/nginx.conf |
sed "s#goonjs-slim-eloquent#${PROJECT_NAME}#g" |
sed "s#goonjs.slimeloquent.dev#${SERVER_NAME}#g" > ${ABS_PATH}/temp.conf

# move nginx config to nginx config path
sudo mv ${ABS_PATH}/temp.conf ${NGINX_CONFIG_PATH}/${PROJECT_NAME}.conf && echo "Finished! Enable your config and restart nginx to run your project" ||
( echo "##### start config content #####" && cat ${ABS_PATH}/temp.conf && echo "##### end config content #####" && echo "Terminated! Nginx config has not been setup. Use the above config to create manually." && rm ${ABS_PATH}/temp.conf )
