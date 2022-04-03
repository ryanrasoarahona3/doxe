#!/usr/bin/env python
# -*- coding: utf-8 -*-




from fpdf import FPDF, HTMLMixin

class PDF(FPDF, HTMLMixin):
        def header(this):
                # Logo
               
                this.set_font('Helvetica','B',12)
                # Move to the right
                this.cell(80)
                this.set_font('Helvetica','I',10)
                this.cell(0,0,'DAte / TITRE',0,0,'R')
                this.ln(10)
               

        # Page footer
        def footer(this):
                # Position at 1.5 cm from bottom
                this.set_y(-20)
                this.set_font('Helvetica','I',8)
                this.cell(0,20,'Page '+str(this.page_no())+' / {nb}',0,0,'R')
               	this.cell(0)
               	this.ln(1)
               	this.set_text_color(233 ,76,61)
               	this.set_draw_color(233 ,76,61)
               	this.cell(0,10,'1, Rue Houdon - 75008 Paris - Tél : 01 53 70 66 63 - www.fondation-benevolat.fr','T',0,'C')
                this.ln(1)
                this.cell(0,16,' Fondation reconnue d\'Utilité publique par décret du 5 mai 1995',0,0,'C')
               	
               	

# Instanciation of inherited class
pdf=PDF()
pdf.alias_nb_pages()
pdf.add_page()
pdf.image('/Applications/MAMP/htdocs/fondation.dev/gestion/documents/images/logo-fondation.jpg',92,5,30)
pdf.ln(50)
pdf.set_font('Helvetica','B',12)
pdf.cell(0,0,'DAte / TITRE',0,0,'C')
pdf.cell(80)
pdf.ln(20)
pdf.set_font('Helvetica','',10)

pdf.write_html('texte')
pdf.add_page()
pdf.write_html('texte')
pdf.output('/Applications/MAMP/htdocs/fondation.dev/documents/test.pdf','F')
