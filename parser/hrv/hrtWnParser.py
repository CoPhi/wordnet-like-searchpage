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
#        |--TYPE the relation type





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
        self.__DOMAIN__ = "DOMAIN" 
        self.__TYPE__ = "TYPE" 
        self.__senseAttr__ = "sense"
        self.__typeAttr__ = "type"
        self.__hasSynId__= 0
        self.__synsetList = []
        self.__synsetTgtList = []
        self.__synonymList = []
        self.__ilrList = []
        
        self.__lastSynset = 0
        self.__lastName = ""
        self.__fatherName = ""
        self.__imSyn = 0
        self.__imType = 0
        self.__imSynonym = 0
        self.__imSense = 0
        self.__imPos = 0
        self.__imLiteral = 0
        self.__imDef = 0
        self.__imILR = 0
        self.__imID = 0
        self.__imDomain = 0
        self.__theContent = ""
        self.__theSenseContent = ""
        self.__theSenseAttrValue = ""
        self.__theTypeContent = ""
        self.__theTypeAttrValue = ""
        self.__theSynContent = ""
        self.__thePosContent = ""
        self.__theDefContent = ""
        self.__theDomainContent = ""
        self.__theLiteralContent = ""
        self.__theTgtSynsetContent = ""
        self.__debug=debug
        self.__hasSenseAttr=0
        self.__somethingWrongWithSense=0
        self.__hasTypeAttr=0
        self.__somethingWrongWithType=0
        self.__sep="%#%".encode('utf-8')
        
        #dictionaries
        self.__sysns = collections.defaultdict(list) # list of synset
        self.__senses = collections.defaultdict(set) # list of senses
        self.__dirtysenses = collections.defaultdict(set) # list of senses with something wrong, namely multiple sense for the same word in the same synset
        self.__dirtyilrs = collections.defaultdict(set) # list of relations
        self.__tgtSyns = collections.defaultdict(list) # list of synset target
        self.__ilrs = collections.defaultdict(set) # list of relations
        self.__domains = collections.defaultdict(list) # list of domains
        self.__poses = collections.defaultdict(set) # list of poses
        self.__defs = collections.defaultdict(list) # list of poses
        
        #sets
        self.__posSet=Set();
        self.__wordsSet=Set();

    def get_dirtyilrs(self):
        return self.__dirtyilrs


    def set_dirtyilrs(self, value):
        self.__dirtyilrs = value


    def del_dirtyilrs(self):
        del self.__dirtyilrs


    def get_the_type_content(self):
        return self.__theTypeContent


    def get_the_type_attr_value(self):
        return self.__theTypeAttrValue


    def set_the_type_content(self, value):
        self.__theTypeContent = value


    def set_the_type_attr_value(self, value):
        self.__theTypeAttrValue = value


    def del_the_type_content(self):
        del self.__theTypeContent


    def del_the_type_attr_value(self):
        del self.__theTypeAttrValue


    def get_the_tgt_synset_content(self):
        return self.__theTgtSynsetContent


    def set_the_tgt_synset_content(self, value):
        self.__theTgtSynsetContent = value


    def del_the_tgt_synset_content(self):
        del self.__theTgtSynsetContent


    def get_dirtysenses(self):
        return self.__dirtysenses


    def set_dirtysenses(self, value):
        self.__dirtysenses = value


    def del_dirtysenses(self):
        del self.__dirtysenses


    def get_the_sense_content(self):
        return self.__theSenseContent


    def get_the_sense_attr_value(self):
        return self.__theSenseAttrValue


    def set_the_sense_content(self, value):
        self.__theSenseContent = value


    def set_the_sense_attr_value(self, value):
        self.__theSenseAttrValue = value


    def del_the_sense_content(self):
        del self.__theSenseContent


    def del_the_sense_attr_value(self):
        del self.__theSenseAttrValue


    def get_the_literal_content(self):
        return self.__theLiteralContent


    def get_words_set(self):
        return self.__wordsSet


    def set_the_literal_content(self, value):
        self.__theLiteralContent = value


    def set_words_set(self, value):
        self.__wordsSet = value


    def del_the_literal_content(self):
        del self.__theLiteralContent


    def del_words_set(self):
        del self.__wordsSet


    def get_father_name(self):
        return self.__fatherName


    def set_father_name(self, value):
        self.__fatherName = value


    def del_father_name(self):
        del self.__prevName


    def get_the_def_content(self):
        return self.__theDefContent


    def get_the_domain_content(self):
        return self.__theDomainContent


    def get_defs(self):
        return self.__defs


    def get_pos_set(self):
        return self.__posSet


    def set_the_def_content(self, value):
        self.__theDefContent = value


    def set_the_domain_content(self, value):
        self.__theDomainContent = value


    def set_defs(self, value):
        self.__defs = value


    def set_pos_set(self, value):
        self.__posSet = value


    def del_the_def_content(self):
        del self.__theDefContent


    def del_the_domain_content(self):
        del self.__theDomainContent


    def del_defs(self):
        del self.__defs


    def del_pos_set(self):
        del self.__posSet


    def get_pos_set(self):
        return self.__posSet


    def set_pos_set(self, value):
        self.__posSet = value


    def del_pos_set(self):
        del self.__posSet


    def get_the_pos_content(self):
        return self.__thePosContent


    def set_the_pos_content(self, value):
        self.__thePosContent = value


    def del_the_pos_content(self):
        del self.__thePosContent


    def get_poses(self):
        return self.__poses


    def set_poses(self, value):
        self.__poses = value


    def del_poses(self):
        del self.__poses


    def get_im_domain(self):
        return self.__imDomain


    def get_domains(self):
        return self.__domains


    def set_im_domain(self, value):
        self.__imDomain = value


    def set_domains(self, value):
        self.__domains = value


    def del_im_domain(self):
        del self.__imDomain


    def del_domains(self):
        del self.__domains


    def get_im_synonym(self):
        return self.__imSynonym


    def set_im_synonym(self, value):
        self.__imSynonym = value


    def del_im_synonym(self):
        del self.__imSynonym


    def get_synset_list(self):
        return self.__synsetList


    def get_synset_tgt_list(self):
        return self.__synsetTgtList


    def get_synonym_list(self):
        return self.__synonymList


    def get_ilr_list(self):
        return self.__ilrList


    def get_last_synset(self):
        return self.__lastSynset


    def get_last_name(self):
        return self.__lastName


    def get_im_syn(self):
        return self.__imSyn


    def get_im_sense(self):
        return self.__imSense


    def get_im_pos(self):
        return self.__imPos


    def get_im_literal(self):
        return self.__imLiteral


    def get_im_def(self):
        return self.__imDef


    def get_im_ilr(self):
        return self.__imILR


    def get_im_id(self):
        return self.__imID


    def get_the_content(self):
        return self.__theContent


    def get_the_syn_content(self):
        return self.__theSynContent


    def get_sysns(self):
        return self.__sysns


    def get_senses(self):
        return self.__senses


    def get_tgt_syns(self):
        return self.__tgtSyns


    def get_ilrs(self):
        return self.__ilrs


    def set_synset_list(self, value):
        self.__synsetList = value


    def set_synset_tgt_list(self, value):
        self.__synsetTgtList = value


    def set_synonym_list(self, value):
        self.__synonymList = value


    def set_ilr_list(self, value):
        self.__ilrList = value


    def set_last_synset(self, value):
        self.__lastSynset = value


    def set_last_name(self, value):
        self.__lastName = value


    def set_im_syn(self, value):
        self.__imSyn = value


    def set_im_sense(self, value):
        self.__imSense = value


    def set_im_pos(self, value):
        self.__imPos = value


    def set_im_literal(self, value):
        self.__imLiteral = value


    def set_im_def(self, value):
        self.__imDef = value


    def set_im_ilr(self, value):
        self.__imILR = value


    def set_im_id(self, value):
        self.__imID = value


    def set_the_content(self, value):
        self.__theContent = value


    def set_the_syn_content(self, value):
        self.__theSynContent = value


    def set_sysns(self, value):
        self.__sysns = value


    def set_senses(self, value):
        self.__senses = value


    def set_tgt_syns(self, value):
        self.__tgtSyns = value


    def set_ilrs(self, value):
        self.__ilrs = value


    def del_synset_list(self):
        del self.__synsetList


    def del_synset_tgt_list(self):
        del self.__synsetTgtList


    def del_synonym_list(self):
        del self.__synonymList


    def del_ilr_list(self):
        del self.__ilrList


    def del_last_synset(self):
        del self.__lastSynset


    def del_last_name(self):
        del self.__lastName


    def del_im_syn(self):
        del self.__imSyn


    def del_im_sense(self):
        del self.__imSense


    def del_im_pos(self):
        del self.__imPos


    def del_im_literal(self):
        del self.__imLiteral


    def del_im_def(self):
        del self.__imDef


    def del_im_ilr(self):
        del self.__imILR


    def del_im_id(self):
        del self.__imID


    def del_the_content(self):
        del self.__theContent


    def del_the_syn_content(self):
        del self.__theSynContent


    def del_sysns(self):
        del self.__sysns


    def del_senses(self):
        del self.__senses


    def del_tgt_syns(self):
        del self.__tgtSyns


    def del_ilrs(self):
        del self.__ilrs


    


    

        
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
        # WN and SYNSET have no attribute
        if name==self.__ROOT__: # I'm in root
            self.__imSyn=0
            self.__imID=0 
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
        
        elif name == self.__SYNSET__:
            self.__imSyn=1
            self.__imID=0 
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.set_father_name(self.__ROOT__)
        
        elif name == self.__ID__:
            self.__imSyn=0
            self.__imID=1
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.set_father_name(self.__SYNSET__)
            
        elif name == self.__POS__:
            self.__imSyn=0
            self.__imID=0
            self.__imPos=1
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.set_father_name(self.__SYNSET__)
        
        elif name == self.__SYNONYM__:
            self.__imSyn=0
            self.__imID=0
            self.__imPos=0
            self.__imSynonym = 1
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.set_father_name(self.__SYNSET__)
        
        elif name == self.__LITERAL__:
            self.__imSyn=0
            self.__imID=0
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=1
            self.__imSense=0
            self.__imDef=0
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.__hasSenseAttr=0
            self.set_father_name(self.__SYNONYM__)
            # manage the sense attribute
           
            for attrName in attrs.keys():
                if attrName==self.__senseAttr__:
                    self.__hasSenseAttr=1
                    if (self.get_the_literal_content()==""):
                        value = attrs.get(attrName).encode('utf-8')
                        self.set_the_sense_attr_value(value);

            # if the sense attribute is never found            
            if (self.__hasSenseAttr==0):
                #no sense attribute...
                self.__somethingWrongWithSense=0
                if self.__debug==1:
                    print "\t\tThe synset "+self.get_the_syn_content()+ " has one literal with no sense attributes"   
                     
            
        elif name == self.__SENSE__:
            self.__imSyn=0
            self.__imID=0
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=1
            self.__imDef=0            
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.set_father_name(self.__LITERAL__)
            
             
        elif name == self.__DEF__:
            self.__imSyn=0
            self.__imID=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imPos=0
            self.__imDef=1            
            self.__imILR=0
            self.__imDomain=0
            self.__imType = 0
            self.set_father_name(self.__SYNSET__)
        
        elif name == self.__ILR__:
            self.__imSyn=0
            self.__imID=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imPos=0
            self.__imDef=0
            self.__imILR=1
            self.__imDomain=0
            self.__imType = 0
            self.__hasTypeAttr=0
            self.set_father_name(self.__SYNSET__)
            
            for attrName in attrs.keys():
               
                if attrName==self.__typeAttr__:
                    
                    self.__hasTypeAttr=1
                    if (self.get_the_tgt_synset_content()==""):
                        value = attrs.get(attrName).encode('utf-8')
                        self.set_the_type_attr_value(value);
                        
            if (self.__hasTypeAttr==0):
                pass
                #no sense attribute...
                if self.__debug==1:
                    print "\t\tThe synset "+self.get_the_syn_content()+ " has one ILR with no type attributes"        
            
        elif name == self.__TYPE__:
           # print "XXXX LAST START "+self.__lastName + " "+str(self.__imILR)
            self.__imSyn=0
            self.__imID=0
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0            
            self.__imDomain=0
            if self.__imILR==1:
                
                self.__imType = 1
                self.set_father_name(self.__ILR__)
            else: # found a type with a different father    
                self.__imType = 0
                self.__imILR=0
                self.set_father_name("") # a different
        elif name == self.__DOMAIN__:
            self.__imSyn=0
            self.__imID=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imPos=0
            self.__imDef=0
            self.__imSense=0
            self.__imILR=0
            self.__imDomain=1
            self.__imType = 0  
            self.set_father_name(self.__SYNSET__)              
         
        else:
            # reset
            self.__imSyn=0
            self.__imID=0
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0            
            self.__imILR=0
            self.__imDomain=0
            self.__imType=0
            self.set_father_name("");
            if (self.__debug==1):
                print    "I'm in start element for "+name + " with parent "+self.__fatherName
               
        #print "start "+name
    def endElement(self, name):
        locV=self.get_the_syn_content()
        value=""
        if (self.__debug==1):
            print "I'm in the endElement for "+name
            locV=""
            # get the ID of synset and save it as last synsetid
        if name==self.__ROOT__:
            pass
        elif name == self.__ID__:
            pass
        elif name==self.__SYNSET__:
            temp=self.get_synset_list()
            temp.append(self.get_the_syn_content())
            self.set_synset_list(temp)
            self.__imSyn=0
            #print "XXXX "+self.get_the_syn_content()
            self.set_father_name(self.__ROOT__)
        elif name==self.__POS__:
            temp=self.get_pos_set()
            temp.add(self.get_the_pos_content())
            self.set_pos_set(temp)
            syn2pos=self.get_poses()
            syn2pos[self.get_the_syn_content()].add(self.get_the_pos_content())
            self.set_poses(syn2pos)
            self.__imPos=0
            self.set_father_name(self.__SYNSET__)
        elif name==self.__DEF__:
            syn2def=self.get_defs()
            syn2def[self.get_the_syn_content()].append(self.get_the_def_content())
            self.set_defs(syn2def)
            self.__imDef=0
            self.set_father_name(self.__SYNSET__)
        elif name==self.__DOMAIN__:
            syn2dom=self.get_domains()
            syn2dom[self.get_the_syn_content()].append(self.get_the_domain_content())
            self.set_domains(syn2dom)
            self.__imDomain=0
            self.set_father_name(self.__SYNSET__)
        elif name==self.__SYNONYM__:
            self.set_the_literal_content("")
            self.__imSynonym=0
            self.set_father_name(self.__SYNSET__)    
            
        elif name==self.__LITERAL__:
            temp=self.get_words_set()
            temp.add(self.get_the_literal_content())
            self.set_words_set(temp)
            if (self.__hasSenseAttr==1):
               # print "XXX Inserting into SENSE "+self.get_the_sense_attr_value()+self.__sep+self.get_the_literal_content()+ " for "+self.get_the_syn_content() + " and check sense - "+str(self.__hasSenseAttr)+ " - "
                tempS=self.get_senses();
                tempS[self.get_the_syn_content()].add(self.get_the_literal_content()+self.__sep+self.get_the_sense_attr_value())
                self.set_senses(tempS)
            if (self.__hasSenseAttr==0):
                #print "XXX Inserting into SENSE "+self.get_the_sense_content()+self.__sep+self.get_the_literal_content()+ " for "+self.get_the_syn_content() + " and check sense - "+str(self.__hasSenseAttr)+ " - "
                tempS=self.get_senses();
                tempS[self.get_the_syn_content()].add(self.get_the_literal_content()+self.__sep+self.get_the_sense_content())
                self.set_senses(tempS)
            self.__imLiteral==0    
            self.set_the_literal_content("")
            self.set_father_name(self.__SYNONYM__)    
            
        elif name==self.__SENSE__:
            if (self.__hasSenseAttr==1): 
               # print "XXX Inserting into DIRTY SENSE "+self.get_the_sense_content()+self.__sep+self.get_the_literal_content()+ " for "+self.get_the_syn_content() + " and check sense - "+str(self.__hasSenseAttr)+ " - "
                tempDS=self.get_dirtysenses();
                tempDS[self.get_the_syn_content()].add(self.get_the_literal_content()+self.__sep+self.get_the_sense_content())
                self.set_dirtysenses(tempDS)
            self.__imSense=0
            self.set_father_name(self.__LITERAL__)
        
        elif name==self.__ILR__:
            if self.__hasTypeAttr==1:
               # print "XXX Inserting into RELS "+self.get_the_type_attr_value()+self.__sep+self.get_the_tgt_synset_content() + " for " +self.get_the_syn_content() + " and check type -"+str(self.__hasTypeAttr)
                tempS=self.get_ilrs();
                tempS[self.get_the_syn_content()].add(self.get_the_type_attr_value()+self.__sep+self.get_the_tgt_synset_content())
                self.set_ilrs(tempS)
            if self.__hasTypeAttr==0:
             #   print "XXX Inserting into RELS "+self.get_the_type_content()+self.__sep+self.get_the_tgt_synset_content() + " for " +self.get_the_syn_content() + " and check type -"+str(self.__hasTypeAttr)
                tempS=self.get_ilrs();
                tempS[self.get_the_syn_content()].add(self.get_the_type_content()+self.__sep+self.get_the_tgt_synset_content())
                self.set_ilrs(tempS)   
                
            self.__imILR=0
            self.set_father_name(self.__SYNSET__)
            self.set_the_tgt_synset_content("")
        elif name==self.__TYPE__ :
            #print "XXXX LAST STOP "+self.__lastName + " "+str(self.__imILR)+ " - "+self.get_the_type_content()
            if self.__imILR==1: # I'm in Type but the parent is ILR
                if (self.__hasTypeAttr==1):
                    #print "XXX Inserting into DIRTY RELS "+self.get_the_type_content()+self.__sep+self.get_the_tgt_synset_content() + " for " +self.get_the_syn_content() + " and check type -"+str(self.__hasTypeAttr)
                    tempDR=self.get_dirtyilrs();
                    tempDR[self.get_the_syn_content()].add(self.get_the_type_content()+self.__sep+self.get_the_tgt_synset_content())
                    self.set_dirtyilrs(tempDR)
                self.set_father_name(self.__ILR__)    
            else:
                self.set_father_name("")
                    
            self.__imType=0
                
                  
        else:
            if (self.__debug==1):
                print    "I'm in end element for "+name + " with parent "+self.__fatherName 
            # reset
            self.__imSyn=0
            self.__imID=0
            self.__imPos=0
            self.__imSynonym = 0
            self.__imLiteral=0
            self.__imSense=0
            self.__imDef=0            
            self.__imILR=0
            self.__imDomain=0
            self.__imType=0
            self.set_father_name("");
                   
               
            
            
            
                                    
                    
          
    def characters(self, content):
        
        
        if content.strip() != "":
            self.set_the_content(content.encode('utf-8'))
            if (self.__debug==1 and content <> "\n"):
                print "I'm in the characters for "+self.__lastName + " and content - "+content+ " - "
            if self.__lastName ==self.__ROOT__:
                pass 
            elif self.__lastName==self.__ID__:
                self.set_the_syn_content(content.encode('utf-8'))
            elif self.__lastName==self.__POS__:
                self.set_the_pos_content(content.encode('utf-8'))
            elif self.__lastName==self.__DEF__:
                self.set_the_def_content(content.encode('utf-8'))    
            elif self.__lastName==self.__DOMAIN__:
                self.set_the_domain_content(content.encode('utf-8'))
            elif self.__lastName==self.__LITERAL__:
                #self.__hasSenseAttr=0
                self.set_the_literal_content(content.encode('utf-8'))
            elif self.__lastName==self.__SENSE__:
                #self.__hasSenseAttr=0
                self.set_the_sense_content(content.encode('utf-8'))               
            elif self.__lastName==self.__ILR__:
                #self.__hasSenseAttr=0
                self.set_the_tgt_synset_content(content.encode('utf-8'))
            elif self.__lastName==self.__TYPE__ :
                
                if self.__imILR==1:
                    #print "XXXX "+str(self.__imILR)+ " XXX "
                #self.__hasSenseAttr=0
                    self.set_the_type_content(content.encode('utf-8')) 
            else:
                if (self.__debug==1):
                    print    "I'm in character for "+self.__lastName + " with parent "+self.__fatherName        
                    
                    
                    
    # properties                           
    synsetList = property(get_synset_list, set_synset_list, del_synset_list, "synsetList's docstring")
    synsetTgtList = property(get_synset_tgt_list, set_synset_tgt_list, del_synset_tgt_list, "synsetTgtList's docstring")
    synonymList = property(get_synonym_list, set_synonym_list, del_synonym_list, "synonymList's docstring")
    ilrList = property(get_ilr_list, set_ilr_list, del_ilr_list, "ilrList's docstring")
    lastSynset = property(get_last_synset, set_last_synset, del_last_synset, "lastSynset's docstring")
    lastName = property(get_last_name, set_last_name, del_last_name, "lastName's docstring")
    imSyn = property(get_im_syn, set_im_syn, del_im_syn, "imSyn's docstring")
    imSense = property(get_im_sense, set_im_sense, del_im_sense, "imSense's docstring")
    imPos = property(get_im_pos, set_im_pos, del_im_pos, "imPos's docstring")
    imLiteral = property(get_im_literal, set_im_literal, del_im_literal, "imLiteral's docstring")
    imDef = property(get_im_def, set_im_def, del_im_def, "imDef's docstring")
    imILR = property(get_im_ilr, set_im_ilr, del_im_ilr, "imILR's docstring")
    imID = property(get_im_id, set_im_id, del_im_id, "imID's docstring")
    theContent = property(get_the_content, set_the_content, del_the_content, "theContent's docstring")
    theSynContent = property(get_the_syn_content, set_the_syn_content, del_the_syn_content, "theSynContent's docstring")
    sysns = property(get_sysns, set_sysns, del_sysns, "sysns's docstring")
    senses = property(get_senses, set_senses, del_senses, "senses's docstring")
    tgtSyns = property(get_tgt_syns, set_tgt_syns, del_tgt_syns, "tgtSyns's docstring")
    ilrs = property(get_ilrs, set_ilrs, del_ilrs, "ilrs's docstring")
    imSynonym = property(get_im_synonym, set_im_synonym, del_im_synonym, "imSynonym's docstring")
    imDomain = property(get_im_domain, set_im_domain, del_im_domain, "imDomain's docstring")
    domains = property(get_domains, set_domains, del_domains, "domains's docstring")
    poses = property(get_poses, set_poses, del_poses, "poses's docstring")
    thePosContent = property(get_the_pos_content, set_the_pos_content, del_the_pos_content, "thePosContent's docstring")
    poseSet = property(get_pos_set, set_pos_set, del_pos_set, "posSet's docstring")
    theDefContent = property(get_the_def_content, set_the_def_content, del_the_def_content, "theDefContent's docstring")
    theDomainContent = property(get_the_domain_content, set_the_domain_content, del_the_domain_content, "theDomainContent's docstring")
    defs = property(get_defs, set_defs, del_defs, "defs's docstring")
    posSet = property(get_pos_set, set_pos_set, del_pos_set, "posSet's docstring")
    prevName = property(get_father_name, set_father_name, del_father_name, "prevName's docstring")
    theLiteralContent = property(get_the_literal_content, set_the_literal_content, del_the_literal_content, "theLiteralContent's docstring")
    wordsSet = property(get_words_set, set_words_set, del_words_set, "wordsSet's docstring")
    theSenseContent = property(get_the_sense_content, set_the_sense_content, del_the_sense_content, "theSenseContent's docstring")
    theSenseAttrValue = property(get_the_sense_attr_value, set_the_sense_attr_value, del_the_sense_attr_value, "theSenseAttrValue's docstring")
    dirtysenses = property(get_dirtysenses, set_dirtysenses, del_dirtysenses, "dirtysenses's docstring")
    theTgtSynsetContent = property(get_the_tgt_synset_content, set_the_tgt_synset_content, del_the_tgt_synset_content, "theTgtSynsetContent's docstring")
    theTypeContent = property(get_the_type_content, set_the_type_content, del_the_type_content, "theTypeContent's docstring")
    theTypeAttrValue = property(get_the_type_attr_value, set_the_type_attr_value, del_the_type_attr_value, "theTypeAttrValue's docstring")
    dirtyilrs = property(get_dirtyilrs, set_dirtyilrs, del_dirtyilrs, "dirtyilrs's docstring")
            
            
        
   

#other methods

# runMe
# starting functions
def parseMe(inFileName, lDebug,sDebug,pDebug):
    print lDebug
    outFile = sys.stdout
    numSyn=0
    numPos=0
    numDef=0
    numDomain=0
    numSense=0
    numRel=0
    numDSense=0
    numDRel=0
    numWord=0
    
#     itaoutFile = sys.stdout
#     engoutFile = sys.stdout
#     latoutFile = sys.stdout
#     grcoutFile = sys.stdout
#     synoutFile = sys.stdout
    
    synsetsFile = "synsets.csv"
    wordsFile = "words.csv"
    tempSenseFile = "tempSense.csv"
    tempRelFile = "tempRel.csv"
    dirtySenseFile = "dirtySense.csv"
    dirtyRelFile = "dirtyRel.csv"
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
    
    # getting variable
    #synsets
    syns=handler.get_synset_list()
    numSyn=len(syns)
    
    #pos
    syn2pos=handler.get_poses()
    
    #words
    ws=handler.get_words_set()
    numWord=len(ws)
    
    #synonyms
    syn2sense=handler.get_senses()
    
    #relations
    syn2ilrs=handler.get_ilrs()
    
    #dirty senses
    syn2ds=handler.get_dirtysenses()
    
    #dirty relations
    syn2dr=handler.get_dirtyilrs()
    
    #definitions
    syn2def=handler.get_defs()
    
    # create files
    # sysnets
    # structure
    #    synsetid
    #    definition
    #    pos
    #    lexdomainid # fixed to 0
   
    
    # open file
    synoutFile = open(synsetsFile,'w')
    for s in syns:
        id=manageids.returnHWNId(s)
        pos="-"
        if len(s.split("-"))==3:
            pos=s.split("-")[2]
        lex='0'
        gloss=''
        test=syn2def.get(s)
        if test is not None:
            gloss=test[0]
        synoutFile.write('%s' % (id+sep+gloss+sep+pos+sep+lex+"\n",))    
             
    synoutFile.close()
    
    
    # words
    # structure
    #    lemma
     
    # open file
    wordoutFile = open(wordsFile,'w')
    for w in ws:
        if w != "":
            wordoutFile.write('%s' % (w+"\n",)) 
    wordoutFile.close()
    
    #sense
#   synsetid
#   lemma 
#   sensenum
#   lexid=sensenum
#   tagcount fixed 0
#   sensekey= lemma%pos as number:sensenum
    tempSenseoutFile=open(tempSenseFile,'w')
    for s, vals in syn2sense.iteritems():
        pos=manageids.returnHWNPos(s)
        sensekey=""
        id=manageids.returnHWNId(s)
        tagcount='0'
        for v in vals:
            sense=v.split("%#%")
            sn=sense[1]
            lemma=sense[0]
            sensekey=lemma+"%"+pos+":"+sn
            tempSenseoutFile.write('%s' % (id+sep+v+sep+sn+sep+tagcount+sep+sensekey+"\n",)) 
            numSense=numSense+1
    tempSenseoutFile.close()
    
    #tem relations
    #structure
    # synset1
    # relation
    # synset2
    tempReloutFile=open(tempRelFile,'w')
    for s, vals in syn2ilrs.iteritems():
        id=manageids.returnHWNId(s)
        for v in vals:
            rel=v.split("%#%")
            rt = rel[0]
            tid=rel[1]
            tid=manageids.returnHWNId(tid)
            tempReloutFile.write('%s' % (id+sep+rt+sep+tid+"\n",)) 
            numRel=numRel+1
    
    tempReloutFile.close()
    
    #something wrong?
    if (len(syn2ds)>0 or len(syn2dr)>0):
        handler.outfile.write('\n')
        handler.outfile.write('*** Something gone bad: multiple sense and/or type of relations \n')
        handler.outfile.write('\n')
        handler.outfile.write('---------------------------\n')
        if len(syn2ds)>0:
            # write the file
            dirtySenseoutFile=open(dirtySenseFile,'w')
            for s, vals in syn2ds.iteritems():
                pos=manageids.returnHWNPos(s)
                sensekey=""
                id=manageids.returnHWNId(s)
                tagcount='0'
                for v in vals:
                    sense=v.split("%#%")
                    sn=sense[1]
                    lemma=sense[0]
                    sensekey=lemma+"%"+pos+":"+sn
                    dirtySenseoutFile.write('%s' % (id+sep+v+sep+sensekey+"\n",)) 
                    numDSense=numDSense+1
            dirtySenseoutFile.close()
            
            handler.outfile.write('# Dirty Senses found (tags <SENSE> in <LITERAL> but <LITERAL> has also the sense attribute):\n')
            handler.outfile.write('\t Dirty Senses: '+str(numDSense) + '\n')
            handler.outfile.write('\n')
        if len(syn2dr)>0:    
            # write the file
            tempReloutFile=open(dirtyRelFile,'w')
            for s, vals in syn2dr.iteritems():
                id=manageids.returnHWNId(s)
                for v in vals:
                    rel=v.split("%#%")
                    rt = rel[0]
                    tid=rel[1]
                    tid=manageids.returnHWNId(tid)
                    tempReloutFile.write('%s' % (id+sep+rt+sep+tid+"\n",)) 
                    numDRel=numDRel+1
    
            tempReloutFile.close()
            
            handler.outfile.write('---------------------------\n')
            handler.outfile.write('# Dirty Relations found (tags <TYPE> in <ILR> but <ILR> has also the type attribute):\n')
            handler.outfile.write('\t Dirty Relations: '+str(numDRel) + '\n')
            handler.outfile.write('---------------------------\n')
        
    
    
    if lDebug==1:
        print "\n...getting the list of synsets...."
        for s in syns:
            print str(manageids.returnHWNId(s))
    
    
        print "\n...getting the part of speeches...."
        for s, vals in syn2pos.iteritems():
            for v in vals:
                print s+ " with pos "+v
             
    
        print "\n...getting the list of words...."       
        for s in ws:
            print str(s)
    
        print "\n...getting the list of senses...."    
        for s, vals in syn2sense.iteritems():
            for v in vals:
                print s+ " with sense "+v
    
        print "\n...getting the list of dirty senses...."  
        
        for s, vals in syn2ds.iteritems():
            for v in vals:
                print s+ " dirty sense "+v 
             
        print "\n...getting the list of relations...."  
        for s, vals in syn2ilrs.iteritems():
            for v in vals:
                print s+ " with relation "+v
    
        print "\n...getting the list of dirty relations...."  
        
        for s, vals in syn2dr.iteritems():
            for v in vals:
                print s+ " dirty dirty relation "+v
      
     # print statistics
    if sDebug==1:
        handler.outfile.write(' \n')
        handler.outfile.write('Statistics\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# Synset found (tag <synset>):\n')
        handler.outfile.write('\t Synset: '+str(numSyn) + '\n') 
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n'  )
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# POS found (tag <literal>):\n')
        handler.outfile.write('\t POS: '+str(numPos) + '\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write(' \n'  )
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# Distinct Words found (tag <literal>):\n')
        handler.outfile.write('\t Distinct Words: '+str(numWord) + '\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# Synonyms found (tags <literal> and <sense>):\n')
        handler.outfile.write('\t Synonyms: '+str(numSense) + '\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# Relations found (tags <ILR> and <TYPE>):\n')
        handler.outfile.write('\t Relations: '+str(numRel) + '\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('\n')
        handler.outfile.write('*** Something gone bad: multiple sense and/or type of relations \n')
        handler.outfile.write('\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# Dirty Senses found (tags <SENSE> in <LITERAL> but <LITERAL> has also the sense attribute):\n')
        handler.outfile.write('\t Dirty Senses: '+str(len(syn2ds)) + '\n')
        handler.outfile.write('\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('# Dirty Relations found (tags <TYPE> in <ILR> but <ILR> has also the type attribute):\n')
        handler.outfile.write('\t Dirty Relations: '+str(len(syn2dr)) + '\n')
        handler.outfile.write('---------------------------\n')
        handler.outfile.write('**** Enf of Statistics *****\n')
                                  
    
#     

# main
def main():
    args = sys.argv[1:]
    if len(args) != 4:
        print 'usage: python hrtWnParse.py infile.xml verbose stat_debug parser_debug'
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
