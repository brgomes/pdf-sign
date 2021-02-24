How to use
==========

Create your .pem public and private files based on your .pfx certificate file. The commands are just below.

Put .pdf file named **source.pdf** on root directory.

Run using the native PHP server:

    php -S localhost:8000

Open the address on navigator. The signed .pdf file it'll call **output.pdf**.


Convert .pfx certificate in .pem files
======================================

**.pfx certificate to PRIVATE .pem:**

    openssl pkcs12 -in filename.pfx -nocerts -out PRIVATE_KEY.pem

**.pfx certificate to PUBLIC .pem:**

    openssl pkcs12 -in filename.pfx -clcerts -nokeys -out PUBLIC_CERT.pem


Both above commands need the certificate password.

To generate private .pem file is necessary to inform a password,
that is used in a PHP constant called CERT_PASSWORD.

About
=====

This repository was created based on [TCPDF project](https://github.com/tecnickcom/TCPDF).

All credits to them.
