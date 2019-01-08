#!/usr/bin/perl -p

use strict;
use warnings;
use utf8::all;

s|The Goteo Mailer|The Impulsa Mailer|g;
s|http://goteo\.org/<br>|https://impulsa.upc.edu/<br>|g;
s|Finançament col\.lectiu amb ADN obert|Impulsant la col·laboració a la UPC|g;
s|Financiación colectiva con ADN abierto|Impulsando la colaboración en la UPC|g;
s|Collective [Ff]inancing with open DNA|Boosting collaboration at the UPC|g;
s|Crowdfunding the Commons|Nexus24 - Comunitats Col·laboratives UPC|g;
s|info\@goteo.org|nexus.24\@upc.edu|g;
s|twitter/identica:|twitter:|g;
s|\@goteofunding|\@nexus24upc|g;
s|hola\@goteo.org|nexus.24\@upc.edu|g;
s|L'equip de Goteo|L'equip d'Impulsa|g;
s|Goteo's team|Impulsa's team|g;
s|plataforma Goteo\.org|plataforma Impulsa UPC|g;
s|plataforma Goteo|plataforma Impulsa|g;
s|Goteo\.org platform|Impulsa UPC platform|g;
s|Goteo platform|Impulsa platform|g;
