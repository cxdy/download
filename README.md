# download
a (very very basic) download code generator/redemption/file download system

## Usage
1. Modify `database.php` to your MySQL details
2. Import `sqldump.sql` to your database
3. Put files in `/var/www/html` or wherever your web server document root resides.
4. ***Definitely*** modify my profanities (all files).
5. Design a front-end. (Submit a pull request if you're feeling frisky).

### Generating codes
I decided on a very insecure method of code generation, simply because what this was built for doesn't care about security.
It goes without saying that the `generate.php` file ***SHOULD NOT BE IN A PUBLIC FACING DIRECTORY***.

**Usage:** `php -f generate.php <number-of-codes-you-want-to-generate>`

And you'll receive an output like the following:
```
Codys-MBP:Download codykaczynski$ php -f generate.php 10
You have generated 10 codes.
Hyqck5arYi5x 
AWjEUlregLdG 
nxk6gpybBdos 
Lczy9fB4DEYr 
ZNhaPjhyQr8u 
vNQCPJN2otwk 
mCDdd3foTW7c 
ydkkT59id2aw 
tQsrhtChYmkf 
FETvrGElCktK
```
