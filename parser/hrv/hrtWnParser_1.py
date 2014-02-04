#! /usr/bin/env python
import sys
import collections
import shutil
import os
import os.path
import hashlib
from sets import Set
import xml.sax
import codecs
import string
import manageids
import datetime
from xml.sax import handler, make_parser

# this script parses the XML of Croatioan Wordnet
# To be a valid XML; the preambol <?xml version="1.0" encoding="UTF-8"?> is added
# as well as the root element <WN> 
#
# The DTD for what concerns our parsing is the following
#
# WN root
#  |--SYNSET the synset
#     |--ID the identifier
#     |--POS part of speech
#     |SYNONYM collection of synonyms
#         |--LITERAL the word in the cluster (NB sometime sense is an attribute)
#             |--SENSE the sense id
#     |--DEF gloss
#     |--ILR relation (semantic and lexical)





# definition of the class
class CroatianWNDocumentHandler(handler.ContentHandler):
    def __init__(self, outfile,debug):
        self.outfile = outfile
#         self.grcoutfile = grcoutfile
#         #self.itaoutfile = itaoutfile
#         self.engoutfile = engoutfile
#         self.latoutfile = latoutfile
#         self.synoutfile = synoutfile
       
        self.__ROOT__ = "WN"
        self.__SYNSET__ = "SYNSET"
        self.__ID__ = "ID"
        self.__POS__ = "POS"
        self.__SYNONYM__ = "SYNONYM" 
        self.__LITERAL__ = "LITERAL" 
        self.__SENSE__ = "SENSE"
        self.__ILR__ = "ILR"   
        self.__DEF__ = "DEF"
        self.__W__ = "w" 
        self.__hasSynId__= 0
        self.__hasLATId__= 0
        self.__hasGRCId__= 0
        self.__hasARAId__= 0
        self.__hasENGId__= 0
        self.__synsetList = []
        self.__synsetTgtList = []
        self.__synonymList = []
        self.__ilrList = []
        
        self.__lastSynset = 0
        self.__lastName = ""
        self.__imLiteral = 0
        self.__theContent = ""
        self.__theSynContent = ""
        self.__debug=debug
        self.__sep="%#%".encode('utf-8')
        
        #dictionaries
        self.__sysns = collections.defaultdict(list) # list of synset
        self.__senses = collections.defaultdict(list) # list of senses
        self.__tgts = collections.defaultdict(list) # list of synset target
        self.__ilrs = collections.defaultdict(list) # list of relations

    def get_has_syn_id__(self):
        return self.__hasSynId__


    def get_ilr_list(self):
        return self.__ilrList


    def get_last_synset(self):
        return self.__lastSynset


    def get_im_literal(self):
        return self.__imLiteral


    def get_the_content(self):
        return self.__theContent


    def get_the_syn_content(self):
        return self.__theSynContent


    def get_sysns(self):
        return self.__sysns


    def get_senses(self):
        return self.__senses


    def get_tgts(self):
        return self.__tgts


    def get_ilrs(self):
        return self.__ilrs


    def get_im_syn(self):
        return self.__imSyn


    def set_has_syn_id__(self, value):
        self.__hasSynId__ = value


    def set_ilr_list(self, value):
        self.__ilrList = value


    def set_last_synset(self, value):
        self.__lastSynset = value


    def set_im_literal(self, value):
        self.__imLiteral = value


    def set_the_content(self, value):
        self.__theContent = value


    def set_the_syn_content(self, value):
        self.__theSynContent = value


    def set_sysns(self, value):
        self.__sysns = value


    def set_senses(self, value):
        self.__senses = value


    def set_tgts(self, value):
        self.__tgts = value


    def set_ilrs(self, value):
        self.__ilrs = value


    def set_im_syn(self, value):
        self.__imSyn = value


    def del_has_syn_id__(self):
        del self.__hasSynId__


    def del_ilr_list(self):
        del self.__ilrList


    def del_last_synset(self):
        del self.__lastSynset


    def del_im_literal(self):
        del self.__imLiteral


    def del_the_content(self):
        del self.__theContent


    def del_the_syn_content(self):
        del self.__theSynContent


    def del_sysns(self):
        del self.__sysns


    def del_senses(self):
        del self.__senses


    def del_tgts(self):
        del self.__tgts


    def del_ilrs(self):
        del self.__ilrs


    def del_im_syn(self):
        del self.__imSyn


    

        
    def startDocument(self):
        if self.__debug==1:
            self.outfile.write(("--------  Document Start --------").encode('utf-8') + "\n")
      
    def endDocument(self):
        if self.__debug==1:
            self.outfile.write(("--------  Document End --------").encode('utf-8') + "\n")
        
    def startElement(self, name, attrs):
        self.__lastName=name
        # check if synset then check Ids. No matter for parent: it is in the DB!!
        if (self.__debug==1):
            print "\nI'm in the startElement for -"+name+"-"
        if name == self.__SYNSET__:
            self.__imSyn==1
            tempAtt=[]
            if (self.__debug==1):
                print "\tEntered in element -"+name+"-"
            for attrName in attrs.keys():
                value = attrs.get(attrName).encode('utf-8')
                if self.__debug==1:
                    self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                if attrName == self.__ID__ or attrName==self.__POS__:  # decode synsetid
                    value = manageids.returnWN30Id(value)
                    self.set_last_synset(value)
                    if self.__debug==1:
                        self.outfile.write('\t\tAttribute -- Name: %s  Rendered: %s\n' % \
                            (attrName.encode('utf-8'), value))
                    if attrName == self.__POS__:
                        value = attrs.get(attrName).encode('utf-8')
                        lastSynset=self.get_lastSynset()
                        value=str(lastSynset)+self.__sep+value
                        self.__synsetList.append(value)
                        self.set_last_synset(value)
                tempAtt.append(attrName.encode('utf-8')+self.__sep+value)    
            self.__synsetList.append(value)
           
            self.set_last_syn_attr_list(tempAtt)
            #print "ESTI "+str(len(self.get_last_syn_attr_list()))        
        if name == self.__SYNONYM__:
            if (self.__debug==1):
                print "\tEntered in element "+name
            
            self.__imLiteral=1
            self.__imGrc=0
            self.__imLat=0
            self.__imAra=0
            self.__imEng=0
            #print "IMITA"
            for attrName in attrs.keys():
                value = attrs.get(attrName).encode('utf-8')
                if self.__debug==1:
                    self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                if attrName == self.__ID__:  # decode synsetid of ita
                    value = manageids.returnIWNId(value)
                    if self.__debug==1:
                        self.outfile.write('\t\tAttribute -- Name: %s  Rendered: %s\n' % \
                            (attrName.encode('utf-8'), value))
                    self.__hasSynId__=1
                    self.__synsetTgtList.append(self.get_last_synset()+self.__sep+value)
                else:
                    self.__hasSynId__=0   
           
        if name == self.__ILR__:
            if (self.__debug==1):
                print "\tEntered in element "+name
            
            self.__imLiteral=0
            self.__imGrc=1
            self.__imLat=0
            self.__imAra=0
            self.__imEng=0
            for attrName in attrs.keys():
                value = attrs.get(attrName).encode('utf-8')
                if self.__debug==1:
                    self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                if attrName == self.__ID__:  # decode synsetid of ita
                    value = manageids.returnGRCId(value)
                    if self.__debug==1:
                        self.outfile.write('\t\tAttribute -- Name: %s  Rendered: %s\n' % \
                            (attrName.encode('utf-8'), value))
                    self.__hasGRCId__=1
                    self.__synonymList.append(self.get_last_synset()+self.__sep+value)
                else:
                    self.__hasGRCId__=0
                  
        
        if name == self.__LITERAL__:
            if (self.__debug==1):
                print "\tEntered in element "+name
            
            self.__imLiteral=0
            self.__imGrc=0
            self.__imLat=1
            self.__imAra=0
            self.__imEng=0
            
            for attrName in attrs.keys():
                value = attrs.get(attrName).encode('utf-8')
                if self.__debug==1:
                    self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                if attrName == self.__ID__:  # decode synsetid of ita
                    value = manageids.returnLATId(value)
                    if self.__debug==1:
                        self.outfile.write('\t\tAttribute -- Name: %s  Rendered: %s\n' % \
                            (attrName.encode('utf-8'), value))
                    self.__hasLATId__=1
                    self.__synsetLatList.append(self.get_last_synset()+self.__sep+value)
                else:
                    self.__hasLATId__=0
            
        if name == self.__SENSE__:
            if (self.__debug==1):
                print "\tEntered in element "+name
            
            self.__imLiteral=0
            self.__imGrc=0
            self.__imLat=0
            self.__imAra=1 
            self.__imEng=0   
            
            for attrName in attrs.keys():
                value = attrs.get(attrName).encode('utf-8')
                if self.__debug==1:
                    self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                if attrName == self.__ID__:  # decode synsetid of ita
                    value = manageids.returnARAId(value)
                    if self.__debug==1:
                        self.outfile.write('\t\tAttribute -- Name: %s  Rendered: %s\n' % \
                            (attrName.encode('utf-8'), value))
                    self.__hasARAId__=1
                    self.__ilrList.append(self.get_last_synset()+self.__sep+value)
                else:
                    self.__hasARAId__=0
                    
        if name == self.__DEF__:
            if (self.__debug==1):
                print "\tEntered in element "+name
            
            self.__imLiteral=0
            self.__imGrc=0
            self.__imLat=0
            self.__imAra=0 
            self.__imEng=1   
            
            for attrName in attrs.keys():
                value = attrs.get(attrName).encode('utf-8')
                if self.__debug==1:
                    self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                if attrName == self.__ID__ :  # decode synsetid of eng
                    value = manageids.returnENGId(value)
                    if self.__debug==1:
                        self.outfile.write('\t\tAttribute -- Name: %s  Rendered: %s\n' % \
                            (attrName.encode('utf-8'), value))
                    self.__hasENGId=1
                    self.__synsetEngList.append(self.get_last_synset()+self.__sep+value)
                else:
                    self.__hasENGId__=0            
         
        # check the words
        if name == self.__W__:
            if self.__imGrc ==1:
                tempAtt=[]
                if self.__debug==1:
                    print "\tManaging words for Greek" 
                for attrName in attrs.keys():
                    value = attrs.get(attrName).encode('utf-8')
                    if self.__debug==1:
                        self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                    
                    #temp[self.get_the_content()].append(self.get_last_synset().encode('utf-8')+"\t"+ attrName.encode('utf-8')+"="+value)
                    tempAtt.append(attrName.encode('utf-8')+self.__sep+value)
                #self.set_grc_ws(temp)
                if self.__debug==1:
                    print "\tList has size "+str(len(tempAtt))
                self.set_last_grc_attr_list(tempAtt)
            
            if self.__imLiteral ==1:
                tempAtt=[]
                if self.__debug==1:
                    print "\tManaging words for Italian"
                for attrName in attrs.keys():
                    value = attrs.get(attrName).encode('utf-8')
                    if self.__debug==1:
                        self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                    
                    #temp[self.get_the_content()].append(self.get_last_synset().encode('utf-8')+"\t"+ attrName.encode('utf-8')+"="+value)
                    tempAtt.append(attrName.encode('utf-8')+self.__sep+value)
                #self.set_grc_ws(temp)
                if self.__debug==1:
                    print "\tList has size "+str(len(tempAtt))
                self.set_last_ita_attr_list(tempAtt)  
                
            
            if self.__imLat ==1:
                tempAtt=[]
                if self.__debug==1:
                    print "\tManaging words for Latin"
                for attrName in attrs.keys():
                    value = attrs.get(attrName).encode('utf-8')
                    if self.__debug==1:
                        self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                    
                    #temp[self.get_the_content()].append(self.get_last_synset().encode('utf-8')+"\t"+ attrName.encode('utf-8')+"="+value)
                    tempAtt.append(attrName.encode('utf-8')+self.__sep+value)
                #self.set_grc_ws(temp)
                if self.__debug==1:
                    print "\tList has size "+str(len(tempAtt))
                self.set_last_lat_attr_list(tempAtt)    
                
                
            if self.__imAra ==1:
                tempAtt=[]
                if self.__debug==1:
                    print "\tManaging words for Arabic"
                for attrName in attrs.keys():
                    value = attrs.get(attrName).encode('utf-8')
                    if self.__debug==1:    
                        self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                    
                    #temp[self.get_the_content()].append(self.get_last_synset().encode('utf-8')+"\t"+ attrName.encode('utf-8')+"="+value)
                    tempAtt.append(attrName.encode('utf-8')+self.__sep+value)
                #self.set_grc_ws(temp)
                if self.__debug==1:
                    print "\tList has size "+str(len(tempAtt))
                self.set_last_ara_attr_list(tempAtt)
                
                
            if self.__imEng ==1:
                tempAtt=[]
                if self.__debug==1:
                    print "\tManaging words for English"
                for attrName in attrs.keys():
                    value = attrs.get(attrName).encode('utf-8')
                    if self.__debug==1:
                        self.outfile.write('\tAttribute -- Name: %s  Value: %s\n' % \
                        (attrName.encode('utf-8'), value))
                    
                    #temp[self.get_the_content()].append(self.get_last_synset().encode('utf-8')+"\t"+ attrName.encode('utf-8')+"="+value)
                    tempAtt.append(attrName.encode('utf-8')+self.__sep+value)
                #self.set_grc_ws(temp)
                if self.__debug==1:
                    print "\tList has size "+str(len(tempAtt))
                self.set_last_eng_attr_list(tempAtt)        
            
               
        #print "start "+name
    def endElement(self, name):
        locV=self.get_the_syn_content()
        value=""
        if (self.__debug==1):
            print "I'm in the endElement for "+name
            locV=""
            
            
        if name == self.__SYNSET__:
            temp=[]
            temp=self.get_last_syn_attr_list()
            tempWs=[]
            tempWs=self.get_syn_ws()
            
            
            if self.__debug==1:
                print "\tManaging Synset attributes and values. Size: "+str(len(tempWs))
            
            if locV=="":
                locV=self.get_last_synset()  
                
            if len(temp)==0:
                tempWs[locV].append(self.get_last_synset()+self.__sep)              
            for att in temp:
                #val=att.split("=")
                #value=self.get_last_synset()+self.__sep+val[0]+self.__sep+val[1]
                #print "esti "+att+ " "+locV+ " "+str(len(temp))
                value=locV+self.__sep+att
                if self.__debug==1:
                    print "\t\t Synset value "+value
                #value=(self.get_last_synset().encode('utf-8')+self.__sep+val[0].encode('utf-8')+self.__sep+val[1].encode('utf-8'))
                    print "\t\t\tManaging Synset attributes and values. Adding: "+value + " to " +locV + " Now is "+ str(len(tempWs))
                tempWs[locV].append(value)
                
            self.set_syn_ws(tempWs)
            if self.__debug==1:
                print "\tManaging Synset attributes and values. Size: "+str(len(tempWs))
                for l in self.get_syn_ws():
                    print "\t\t\tIn Synset List: "+l
            self.__imSyn==0    
                
        if name == self.__SYNONYM__:
            if  self.__hasSynId__== 1:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has link to IWN"
            else:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has link to MWN"   
            self.__hasSynId__=0
            
        if name == self.__LITERAL__:
            if  self.__hasLATId__== 1:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has link to LWN"
            else:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has NO link to LWN"   
            self.__hasLATId__=0  
        
        if name == self.__SENSE__:
            if  self.__hasARAId__== 1:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has link to AWN"
            else:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has NO link to AWN"   
            self.__hasARAId__=0
            
        if name == self.__ILR__:
            if  self.__hasGRCId__== 1:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has link to GWN"
            else:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has NO link to GWN"   
            self.__hasGRCId__=0
            
        if name == self.__DEF__:
            if  self.__hasENGId__== 1:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has link to WN"
            else:
                if self.__debug==1:
                    print "\tThe English synset "+self.get_last_synset()+ " has NO link to WN"   
            self.__hasENGId__=0            
            
        if name == self.__W__:
            if self.__imGrc==1:
                temp=[]
                temp=self.get_last_grc_attr_list()
                tempWs=[]
                tempWs=self.get_grc_ws()
                if self.__debug==1:
                    print "\tManaging Greek attributes and values. Size: "+str(len(tempWs))
                
                if len(temp)==0:
                    tempWs[self.get_the_content()].append(self.get_last_synset()+self.__sep)
                for att in temp:
                    #val=att.split("=")
                    #value=self.get_last_synset()+self.__sep+val[0]+self.__sep+val[1]
                    value=self.get_last_synset()+self.__sep+att
                    if self.__debug==1:
                        print "\t\t Greek value "+value
                    #value=(self.get_last_synset().encode('utf-8')+self.__sep+val[0].encode('utf-8')+self.__sep+val[1].encode('utf-8'))
                    tempWs[self.get_the_content()].append(value)
                self.set_grc_ws(tempWs)
                if self.__debug==1:
                    print "\tManaging Greek attributes and values. Size: "+str(len(tempWs))
                    print "\t\t\tManaging Synset attributes and values. Adding: "+value + " to " +locV + " Now is "+ str(len(tempWs))
                    for l in self.get_grc_ws():
                        print "\t\t\tIn Greek List: "+l
            if self.__imLiteral==1:
                temp=[]
                temp=self.get_last_ita_attr_list()
                tempWs=[]
                tempWs=self.get_ita_ws()
                if self.__debug==1:
                    print "\tManaging Italian attributes and values. Size: "+str(len(tempWs))
                
                if len(temp)==0:
                    tempWs[self.get_the_content()].append(self.get_last_synset()+self.__sep)     
                for att in temp:
                   #val=att.split("=")
                   #value=self.get_last_synset()+self.__sep+val[0]+self.__sep+val[1]
                   value=self.get_last_synset()+self.__sep+att
                   if self.__debug==1:
                        print "\t\t Italian value "+value
                   #value=(self.get_last_synset().encode('utf-8')+self.__sep+val[0].encode('utf-8')+self.__sep+val[1].encode('utf-8'))
                   tempWs[self.get_the_content()].append(value)
                self.set_ita_ws(tempWs)  
                if self.__debug==1:
                    print "\tManaging Italian attributes and values. Size: "+str(len(tempWs))
                    print "\t\t\tManaging Synset attributes and values. Adding: "+value + " to " +locV + " Now is "+ str(len(tempWs))
                    for l in self.get_ita_ws():
                        print "\t\t\tIn Italian List: "+l
                        
            if self.__imLat==1:
                temp=[]
                temp=self.get_last_lat_attr_list()
                tempWs=[]
                tempWs=self.get_lat_ws()
                if self.__debug==1:
                    print "\tManaging latin attributes and values. Size: "+str(len(tempWs))
                     
                if len(temp)==0:
                    tempWs[self.get_the_content()].append(self.get_last_synset()+self.__sep)
                for att in temp:
                   #val=att.split("=")
                   #value=self.get_last_synset()+self.__sep+val[0]+self.__sep+val[1]
                   value=self.get_last_synset()+self.__sep+att
                   if self.__debug==1:
                       print "\t\t Latin value "+value
                   #value=(self.get_last_synset().encode('utf-8')+self.__sep+val[0].encode('utf-8')+self.__sep+val[1].encode('utf-8'))
                   tempWs[self.get_the_content()].append(value)
                self.set_lat_ws(tempWs)  
                if self.__debug==1:
                    print "\tManaging Latin attributes and values. Size: "+str(len(tempWs))
                    print "\t\t\tManaging Synset attributes and values. Adding: "+value + " to " +locV + " Now is "+ str(len(tempWs))
                    for l in self.get_lat_ws():
                        print "\t\t\tIn Latin List: "+l  
                        
            if self.__imAra==1:
                temp=[]
                temp=self.get_last_ara_attr_list()
                tempWs=[]
                tempWs=self.get_ara_ws()
                if self.__debug==1:
                    print "\tManaging Arabic attributes and values. Size: "+str(len(tempWs))
                if len(temp)==0:
                    tempWs[self.get_the_content()].append(self.get_last_synset()+self.__sep)     
                for att in temp:
                   #val=att.split("=")
                   #value=self.get_last_synset()+self.__sep+val[0]+self.__sep+val[1]
                   value=self.get_last_synset()+self.__sep+att
                   if self.__debug==1:
                        print "\t\t Arabic value "+value
                   #value=(self.get_last_synset().encode('utf-8')+self.__sep+val[0].encode('utf-8')+self.__sep+val[1].encode('utf-8'))
                   tempWs[self.get_the_content()].append(value)
                self.set_ara_ws(tempWs)  
                if self.__debug==1:
                    print "\tManaging Arabic attributes and values. Size: "+str(len(tempWs))
                    print "\t\t\tManaging Synset attributes and values. Adding: "+value + " to " +locV + " Now is "+ str(len(tempWs))
                    for l in self.get_ara_ws():
                        print "\t\t\tIn Arabic List: "+l 
                        
                        
            
            if self.__imEng==1:
                temp=[]
                temp=self.get_last_eng_attr_list()
                tempWs=[]
                tempWs=self.get_eng_ws()
                value=""
                if self.__debug==1:
                    print "\tManaging Arabic attributes and values. Size: "+str(len(tempWs))
                     
                if len(temp)==0:
                    tempWs[self.get_the_content()].append(self.get_last_synset()+self.__sep)
                for att in temp:
                   #val=att.split("=")
                   #value=self.get_last_synset()+self.__sep+val[0]+self.__sep+val[1]
                   value=self.get_last_synset()+self.__sep+att
                   if self.__debug==1:
                       print "\t\t English value "+value
                   #value=(self.get_last_synset().encode('utf-8')+self.__sep+val[0].encode('utf-8')+self.__sep+val[1].encode('utf-8'))
                   tempWs[self.get_the_content()].append(value)
                self.set_eng_ws(tempWs)  
                if self.__debug==1:
                    print "\tManaging Arabic attributes and values. Size: "+str(len(tempWs))
                    print "\t\t\tManaging Synset attributes and values. Adding: "+value + " to " +locV + " Now is "+ str(len(tempWs))
                    for l in self.get_eng_ws():
                        print "\t\t\tIn Arabic List: "+l                             
                    
          
    def characters(self, content):
        
        if content.strip() != "":
            self.set_the_content(content.encode('utf-8'))
            if (self.__debug==1):
                print "I'm in the characters for "+self.__lastName + " and content "+content
                if self.__lastName==self.__SYNSET__:
                    self.set_the_syn_content(content.encode('utf-8'))        
            #if (self.__hasSynId__==0) and (self.__imLiteral==1):
             #   print (self.get_last_synset()+"\t"+content.encode('utf-8')).encode('utf-8')
            
        
        # props   
    __synsetList = property(get_synset_list, set_synset_list, del_synset_list, "synsetList is the list of English synsets")
    __lastSynset = property(get_last_synset, set_last_synset, del_last_synset, "lastSynset is the last English synset")
    __synsetTgtList = property(get_synset_ita_list, set_synset_ita_list, del_synset_ita_list, "synsetItaList's docstring")
    __grcWs = property(get_grc_ws, set_grc_ws, del_grc_ws, "grcWs's docstring")
    __itaWs = property(get_ita_ws, set_ita_ws, del_ita_ws, "itaWs's docstring")
    __latWs = property(get_lat_ws, set_lat_ws, del_lat_ws, "latWs's docstring")
    __theContent = property(get_the_content, set_the_content, del_the_content, "theContent's docstring")
    __araWs = property(get_ara_ws, set_ara_ws, del_ara_ws, "araWs's docstring")
    __synonymList = property(get_synset_grc_list, set_synset_grc_list, del_synset_grc_list, "synsetGrcList's docstring")
    __lastGrcAttrList = property(get_last_grc_attr_list, set_last_grc_attr_list, del_last_grc_attr_list, "lastGrcAttrList's docstring")
    __lastItaAttrList = property(get_last_ita_attr_list, set_last_ita_attr_list, del_last_ita_attr_list, "lastItaAttrList's docstring")
    __lastLatAttrList = property(get_last_lat_attr_list, set_last_lat_attr_list, del_last_lat_attr_list, "lastLatAttrList's docstring")
    __lastAraAttrList = property(get_last_ara_attr_list, set_last_ara_attr_list, del_last_ara_attr_list, "lastAraAttrList's docstring")
    __lastSynAttrList = property(get_last_syn_attr_list, set_last_syn_attr_list, del_last_syn_attr_list, "lastSynAttrList's docstring")
    __ilrList = property(get_synset_ara_list, set_synset_ara_list, del_synset_ara_list, "synsetAraList's docstring")
    __synsetLatList = property(get_synset_lat_list, set_synset_lat_list, del_synset_lat_list, "synsetLatList's docstring")
    __synsetEngList = property(get_synset_eng_list, set_synset_eng_list, del_synset_eng_list, "synsetEngList's docstring")
    __lastEngAttrList = property(get_last_eng_attr_list, set_last_eng_attr_list, del_last_eng_attr_list, "lastEngAttrList's docstring")
    __engWs = property(get_eng_ws, set_eng_ws, del_eng_ws, "engWs's docstring")
    __theSynContent = property(get_the_syn_content, set_the_syn_content, del_the_syn_content, "theSynContent's docstring")
    __synWs = property(get_syn_ws, set_syn_ws, del_syn_ws, "synWs's docstring")
    hasSynId__ = property(get_has_syn_id__, set_has_syn_id__, del_has_syn_id__, "hasSynId__'s docstring")
    ilrList = property(get_ilr_list, set_ilr_list, del_ilr_list, "ilrList's docstring")
    lastSynset = property(get_last_synset, set_last_synset, del_last_synset, "lastSynset's docstring")
    imLiteral = property(get_im_literal, set_im_literal, del_im_literal, "imLiteral's docstring")
    theContent = property(get_the_content, set_the_content, del_the_content, "theContent's docstring")
    theSynContent = property(get_the_syn_content, set_the_syn_content, del_the_syn_content, "theSynContent's docstring")
    sysns = property(get_sysns, set_sysns, del_sysns, "sysns's docstring")
    senses = property(get_senses, set_senses, del_senses, "senses's docstring")
    tgts = property(get_tgts, set_tgts, del_tgts, "tgts's docstring")
    ilrs = property(get_ilrs, set_ilrs, del_ilrs, "ilrs's docstring")
    imSyn = property(get_im_syn, set_im_syn, del_im_syn, "imSyn's docstring")

#other methods

# runMe
# starting functions
def parseMe(inFileName, lDebug,sDegug,pDebug):
    outFile = sys.stdout
#     itaoutFile = sys.stdout
#     engoutFile = sys.stdout
#     latoutFile = sys.stdout
#     grcoutFile = sys.stdout
#     synoutFile = sys.stdout
    
    itaWsFile = "itaWs.csv"
    itaMapFile = "itaSynsetXsynsetMap.csv"
    grcWsFile = "grcWs.csv"
    grcMapFile = "grcSynsetXsynsetMap.csv"
    latWsFile = "latWs.csv"
    latMapFile = "latSynsetXsynsetMap.csv"
    araWsFile = "araWs.csv"
    araMapFile = "araSynsetXsynsetMap.csv"
    engWsFile = "engWs.csv"
    engMapFile = "engSynsetXsynsetMap.csv"
    synWsFile = "synWs.csv"
    sep="%#%"
    
    handler = CroatianWNDocumentHandler(outFile,pDebug)
    # Create an instance of the parser.
    parser = make_parser()
    # Set the content handler.
    parser.setContentHandler(handler)
    inFile = open(inFileName, 'r')
    # print "Encoding "+inFile.encoding
    # Start the parse.
    parser.parse(inFile)  # [10]
    # Alternatively, we could directly pass in the file name.
    # parser.parse(inFileName)
    inFile.close()
    
    # get the list of mappings between wn30 and IWN
    itaMsList = handler.get_synset_ita_list()
    itaWs = handler.get_ita_ws()
        
    # get the list of mappings between wn30 and GWN
    grcMsList = handler.get_synset_grc_list()
    grcWs = handler.get_grc_ws()
      
    # get the list of mappings between wn30 and LWN
    latMsList = handler.get_synset_lat_list()
    latWs = handler.get_lat_ws()
        
    # get the list of mappings between wn30 and AWN
    araMsList = handler.get_synset_ara_list()
    araWs = handler.get_ara_ws()    
        
    # get the list of mappings between wn30 and ENG
    engMsList = handler.get_synset_eng_list()
    engWs = handler.get_eng_ws()
    
    # managing synsets
    synWs = handler.get_syn_ws()
    
    # creating features files
    #ita
    itaoutFile = open(itaWsFile,'w')
    for word,atts in itaWs.iteritems():
        for att in atts:
            itaoutFile.write('%s' % (word+sep+att+"\n",))
    itaoutFile.close()
    
    #eng
    engoutFile = open(engWsFile,'w')
    for word,atts in engWs.iteritems():
        for att in atts:
            engoutFile.write('%s' % (word+sep+att+"\n",))
    engoutFile.close()  
    
    #grc
    grcoutFile = open(grcWsFile,'w')
    for word,atts in grcWs.iteritems():
        for att in atts:
            grcoutFile.write('%s' % (word+sep+att+"\n",))
    grcoutFile.close()
    
    #lat
    latoutFile = open(latWsFile,'w')
    for word,atts in latWs.iteritems():
        for att in atts:
            latoutFile.write('%s' % (word+sep+att+"\n",))
    latoutFile.close() 
    
    #ara
    araoutFile = open(araWsFile,'w')
    for word,atts in araWs.iteritems():
        for att in atts:
            araoutFile.write('%s' % (word+sep+att+"\n",))
    araoutFile.close()
    
    #syn
    synoutFile = open(synWsFile,'w')
    for word,atts in synWs.iteritems():
        for att in atts:
            synoutFile.write('%s' % (word+sep+att+"\n",))
    synoutFile.close()
    
    # mapping files
    #ita
    itaoutFile = open(itaMapFile,'w')
    for m in itaMsList:
            itaoutFile.write('%s' % (m,)+"\n")  
    itaoutFile.close() 
    
    #lat
    latoutFile = open(latMapFile,'w')
    for m in latMsList:
            latoutFile.write('%s' % (m,)+"\n")  
    latoutFile.close()
    
    #grc
    grcoutFile = open(grcMapFile,'w')
    for m in grcMsList:
            grcoutFile.write('%s' % (m,)+"\n")  
    grcoutFile.close() 
    
    #ara
    araoutFile = open(araMapFile,'w')
    for m in araMsList:
            araoutFile.write('%s' % (m,)+"\n")  
    araoutFile.close()
    
    #eng
    engoutFile = open(engMapFile,'w')
    for m in araMsList:
            engoutFile.write('%s' % (m,)+"\n")  
    engoutFile.close()
   
    if lDebug ==1: 
        handler.outfile.write('Complete verbose of the execution:\n')
        handler.outfile.write(' \n') 
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n')
        handler.outfile.write('List of features of SYNSET (Namely Attributes in the <synset> tag)\n')
        for word,atts in synWs.iteritems():
            for att in atts:
                handler.outfile.write('    %s' % (word+sep+att+"\n",))
        
       
        handler.outfile.write(' \n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n')
        handler.outfile.write('List of mappings between wn30 and IWN (Namely the mapping between synsetid of WN30 -id in <synset>- and MWN -id in <ita>- if any)\n ')
        for m in itaMsList:
            handler.outfile.write('    %s' % (m,)+"\n")
        
        handler.outfile.write(' \n')
        handler.outfile.write('List of features for words (<w>) of ITA (Namely Attributes in the <ita> tag\n')
        for word,atts in itaWs.iteritems():
            for att in atts:
                handler.outfile.write('    %s' % (word+sep+att+"\n",))
                
        handler.outfile.write(' \n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n')
        handler.outfile.write('List of mappings between wn30 and GWN (Namely the mapping between synsetid of WN30 -id in <synset>- and GWN -id in <grc>- if any)\n ')
        for m in grcMsList:
            handler.outfile.write('    %s' % (m,)+"\n")
        
        handler.outfile.write(' \n')
        handler.outfile.write('List of features for words (<w>) of GRC (Namely Attributes in the <grc> tag\n')
        for word,atts in grcWs.iteritems():
            for att in atts:
                handler.outfile.write('    %s' % (word+sep+att+"\n",))
                
        handler.outfile.write(' \n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n')
        handler.outfile.write('List of mappings between wn30 and LWN (Namely the mapping between synsetid of WN30 -id in <synset>- and LWN -id in <lat>- if any)\n ')
        for m in latMsList:
            handler.outfile.write('    %s' % (m,)+"\n")
        
        handler.outfile.write(' \n')
        handler.outfile.write('List of features for words (<w>) of LAT (Namely Attributes in the <lat> tag\n')
        for word,atts in latWs.iteritems():
            for att in atts:
                handler.outfile.write('    %s' % (word+sep+att+"\n",))
                
        
        handler.outfile.write(' \n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n')
        handler.outfile.write('List of mappings between wn30 and AWN (Namely the mapping between synsetid of WN30 -id in <synset>- and AWN -id in <ara>- if any)\n ')
        for m in araMsList:
            handler.outfile.write('    %s' % (m,)+"\n")
        
        handler.outfile.write(' \n')
        handler.outfile.write('List of features for words (<w>) of ARA (Namely Attributes in the <ara> tag\n')
        for word,atts in araWs.iteritems():
            for att in atts:
                handler.outfile.write('    %s' % (word+sep+att+"\n",))
                
        handler.outfile.write(' \n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n')
        handler.outfile.write('List of mappings between wn30 and Other WN (Namely the mapping between synsetid of WN30 -id in <synset>- and Other WN -id in <eng>- if any)\n ')
        for m in engMsList:
            handler.outfile.write('    %s' % (m,)+"\n")
        
        handler.outfile.write(' \n')
        handler.outfile.write('List of features for words (<w>) of ENG (Namely Attributes in the <eng> tag\n')
        for word,atts in engWs.iteritems():
            for att in atts:
               handler.outfile.write('    %s' % (word+sep+att+"\n",))
                
        handler.outfile.write(' \n')
        handler.outfile.write('---------------------------\n')
        
    if sDegug==1:    
        handler.outfile.write(' \n')
        handler.outfile.write('Statistics\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('Synset found (tag <synset>):\n')
        handler.outfile.write('\t Synset: '+str(len(synWs)) + '\n') 
        handler.outfile.write(' \n'  )
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('Mapping English to:\n')
        handler.outfile.write('\t Italian: '+str(len(itaMsList)) + '\n') 
        handler.outfile.write('\t Latin: '+str(len(latMsList)) + '\n')
        handler.outfile.write('\t Arabic: '+str(len(araMsList))  + '\n')
        handler.outfile.write('\t Greek: '+str(len(grcMsList)) + '\n')
        handler.outfile.write('\t English: '+str(len(engMsList)) + '\n')
        handler.outfile.write('\t Total: '+str(len(itaMsList)+len(latMsList)+len(araMsList)+len(grcMsList)+len(engMsList)) + '\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n'  )
        handler.outfile.write('Words found (tag <w>):\n')
        handler.outfile.write('\t Italian (tag <ita>): '+str(len(itaWs))  + '\n')
        handler.outfile.write('\t Latin (tag <lat>): '+str(len(latWs)) + '\n')
        handler.outfile.write('\t Arabic (tag <ara>): '+str(len(araWs))  + '\n')
        handler.outfile.write('\t Greek (tag <grc>): '+str(len(grcWs)) + '\n')
        handler.outfile.write('\t English (tag <eng>): '+str(len(engWs))    + '\n')
        handler.outfile.write('\t Total: '+str(len(itaWs)+len(latWs)+len(araWs)+len(grcWs)+len(engWs))+ '\n')

# main
def main():
    args = sys.argv[1:]
    if len(args) != 4:
        print 'usage: python gwnParse.py infile.xml verbose stat_debug parser_debug'
        sys.exit(-1)
    print "params "
    print "\t file: "+args[0]
    print "\t Verbose: "+args[1]
    print "\t Debug Statistics?: "+args[2]
    print "\t Debug Parser?: "+args[3]
    now=datetime.datetime.now()
    print '---------------------------'
    print "\t Started: "+now.strftime("%Y-%m-%d %H:%M:%S")
    parseMe(args[0], int(args[1]), int(args[2]), int(args[3]))
    
    now=datetime.datetime.now()
    print '---------------------------'
    print "\t Ended: "+now.strftime("%Y-%m-%d %H:%M:%S")
 
if __name__ == '__main__':
    main()
