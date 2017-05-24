#!/bin/bash
pdflatex -interaction=nonstopmode test.tex && convert -density 300 -quality 100 pdf:test.pdf png:-
