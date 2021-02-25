How to use
==========

    composer install

Create your .crt file based on your .pfx certificate file. The command is just below.

Put .pdf file named **source.pdf** on root directory.

Run using the native PHP server:

    php -S localhost:8000

Open the address on navigator. The signed .pdf file it'll call **output.pdf**.


Convert .pfx certificate in .crt file
=====================================

**.pfx certificate to .crt FILE:**

    openssl pkcs12 -in filename.pfx -out CERTIFICATE.crt -nodes


The command above needs the certificate password.

About
=====

This repository was created based on [TCPDF project](https://github.com/tecnickcom/TCPDF).

All credits to them.
