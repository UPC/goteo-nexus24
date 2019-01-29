#!/usr/bin/perl -p

=pod

=head1 NAME

goteo-impulsa.pl - Adapt goteo.org mail templates to impulsa.upc.edu

=head1 SYNOPSIS

    # Dump mail templates only with separate INSERT statements
    mysqldump --opt --skip-extended-insert --skip-quick goteo template template_lang > goteo.sql

    # Review differences
    perl goteo-impulsa.pl < goteo.sql | diff -u goteo.sql -

    # Commit changes to database
    perl goteo-impulsa.pl < goteo.sql | mysql goteo

=head1 DESCRIPTION

Replaces strings from Goteo.org mail templates to match the
purpose of Impulsa UPC adaptation for collaborative projects.

Since there are so many mail templates, changing them manually
would be a huge task. Thus, we're trying to automate it a bit
by tweaking the strings in the database.

=head1 CAVEATS

Playing with string replacements in any database is tricky and
very dangerous. Please, be careful with your regexes and review
the differences before committing the changes into the database.

=cut

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
