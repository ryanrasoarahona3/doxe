#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys, json
try:
    data = json.loads(sys.argv[1])
except:
    print "ERROR"
    sys.exit(1)

# data 0 = filename
# data 1 = titre
# data 2 = footer
# data 3 = footer2
# data 4 = date
#'/Applications/MAMP/htdocs/fondation.dev/documents/doc.html'

f = open('/Applications/MAMP/htdocs/fondation.dev/documents/'+data[0]+'.html','r')
try:
	string = f.read()
except FileNotFoundError:
	string



from fpdf import FPDF, HTMLMixin

class PDF(FPDF, HTMLMixin):
        def header(this):
                this.set_font('Helvetica','I',8)
                this.cell(0,0,data[1],0,0,'L')
                this.cell(0,0,data[4],0,0,'R')
                this.ln(10)

        # Page footer
        def footer(this):
                this.set_y(-20)
                this.set_font('Helvetica','I',8)
                this.cell(0,20,'Page '+str(this.page_no())+' / {nb}',0,0,'R')
               	this.cell(0)
               	this.ln(1)
               	this.set_text_color(233 ,76,61)
               	this.set_draw_color(233 ,76,61)
               	this.cell(0,10,data[2],'T',0,'C')
                this.ln(1)
                this.cell(0,16,data[3],0,0,'C')
                	

# Instanciation of inherited class
pdf=PDF()
pdf.alias_nb_pages()
pdf.add_page()
pdf.image('/Applications/MAMP/htdocs/fondation.dev/gestion/documents/images/logo-amis.jpg',92,10,30) 
pdf.ln(50)             
pdf.set_font('Helvetica','B',12)
pdf.cell(0,0,data[1],0,0,'C')
pdf.cell(80)
pdf.ln(20)
pdf.set_font('Helvetica','',10)
pdf.write_html(string)

pdf.output('/Applications/MAMP/htdocs/fondation.dev/documents/'+data[0]+'.pdf','F')

#suppression du fichier
import os
os.remove('/Applications/MAMP/htdocs/fondation.dev/documents/'+data[0]+'.html')

# Réponse
result = {'status': 'ok'}
print json.dumps(result)