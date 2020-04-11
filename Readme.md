This is a simple scraper of Walmart online store. Currently only the following item properties are stored into MySQL DB:

* item name
* item price
* rating
* sold by

It could be easily extended to have images, separate 1:n table for that. As well we category table, etc. 

**DISCLAIMER**:This project is done only for educational purposes and was never run for the whole directory of products. The author(s) are not responsible for improper (or against the terms of service of walmart.com) usage.   

### Requirements:

* PHP 7.4 with PDO MySQL driver installed
* PHP bzip2 extension

### Installation and Running

just run `make build` followed by `make run`


