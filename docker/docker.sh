#!/bin/bash

cd "$(dirname "$0")"

prefixFile=`pwd`'/name-prefix.txt'
if [ -e "$prefixFile" ]
then
	PREFIX=`cat "$prefixFile"`
#	echo Container name prefix will be "$PREFIX"
else
	echo "$prefixFile"' not found!'
	exit 1
fi

# Check that environment argument is supplied.
if [ "$1" ]
then
    if [ "$1" == "up" ]
    then
    	
    	if [ -f container_is_build ] 
    	then
    		docker-compose -p $PREFIX start
    		 
    	else
    		docker-compose -p $PREFIX up -d
    		touch container_is_build
    		
    	fi
    	
    elif [ "$1" == "start" ]
    then
        docker-compose -p $PREFIX start
    elif [ "$1" == "stop" ]
    then
        docker-compose -p $PREFIX stop
    elif [ "$1" == "rm" ]
    then
        docker-compose -p $PREFIX rm
    else
        echo "Command not found. Try 'up' for example."
    fi
else
    echo "Command needed as argument. Try 'up' for example."
fi
