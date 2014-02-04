#! /usr/bin/env pythonimport sys
import collections
import shutil
import os
import os.path
import hashlib
from sets import Set
import string
# other utils functions
sep="|"
def returnWN30Id(id):
	pos = id[0]
	other = id[2:]
	if pos == 'n':
    		pos = '1';
	elif pos == 'v':
		pos = '2';
	elif pos == 'a':
      		pos = '3';
	elif pos == 'r':
      		pos = '4';
	elif pos == 's':
      		pos = '5';
	id = pos + other;
	return id;


def returnWN30pos(p):
	pos = ""
	if p == '1':
	    pos = 'n';
	elif p == '2':
		pos = 'v';
	elif p == '3':
	    pos = 'a';
	elif p == '4':
		pos = 'r';
	elif p == '5':
		pos = 's';
	return pos;

def returnIWNId(id):
	lists = id.split("-")
	pos=lists[3]
	id = lists[2]
	return id+sep+pos

def decodePos(pos):
	if pos == 'n':
		pos = '1';
	elif pos == 'v':
		pos = '2';
	elif pos == 'a':
		pos = '3';
	elif pos == 'r':
		pos = '4';
	elif pos == 's':
		pos = '5';
	else:
		pos="0"	
	return pos	

def returnHWNId(id):	
	lists = id.split("-")
	pos="-"
	if len(lists)== 3:
		pos=lists[2]
		id = lists[1]
		pos=decodePos(pos)
	else:
		print "Wrong IDs "+id		
	return pos+id

def returnHWNPos(id):	
	lists = id.split("-")
	pos="-"
	if len(lists)== 3:
		pos=lists[2]
		id = lists[1]
		pos=decodePos(pos)
	else:
		print "Wrong IDs (Pos) "+id		
	return pos

def returnGRCId(id):
	return id

def returnLATId(id):
	return id

def returnARAId(id):
	return id

def returnENGId(id):
	return id

def returnSynsetId(lan,pos,id):
	cod=""
	p=""
	if lan=="grc":
		cod="001"
	if lan=="lat":
		cod="002"
	if lan=="ara":
		cod="003"
	if lan=="ita":
		cod="004"	
	
	if pos=="N":
		p="1"
	if pos=="V":
		p="2"
	if pos=="A":
		p="3"
	if pos=="R":
		p="4"
	if pos=="X":
		p="5"	
	
	return p+cod+id	