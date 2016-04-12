/*global module:false*/
var exec = require('child_process').exec;

module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        // Task configuration.
        phpunit: {
            classes: {
                dir: 'Tests'

            },
            options: {
                bin: 'vendor/bin/phpunit',
                colors: true,
                coverage: false,
                configuration: 'phpunit.xml'
            }
        },
        exec: {
            ldap: {
                command: function() {
                    return './Resources/ldap/tools/in-memory-directory-server --port=1234 --baseDN "dc=example,dc=com" --ldiffile Resources/ldap/sample.ldif &';
                }
            }
        },
        watch: {
            scripts: {
                files: [
                    '**/*.php',
                    'Tests/Entity/Ldap/*.php',
                    'Tests/Fixtures/*.php',
                    'Tests/Ldap/*.php',
                    'Tests/Ldap/Filter*.php',
                    '!Tests/cache/**/*.php',
                    'Resources/**/*.yml'
                ],
                tasks: ['phpunit'],
                options: {
                    interrupt: true
                }
            }
        }
    });

    // These plugins provide necessary tasks.
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-contrib-watch');

    var killed = false;

    grunt.registerTask('ldapProcess', 'In memory LDAP process maintenance.', function(){
        var readLine = require('readline');

        var rl = readLine.createInterface({
            input: process.stdin,
            output: process.stdout
        });

        rl.on('SIGINT', function () {
            process.emit('SIGINT');
        });

        process.on('exit', kill);
        process.on('SIGINT', killAndExit);
        process.on('SIGHUP', killAndExit);
        process.on('SIGBREAK', killAndExit);
    });

    function kill() {
        if (killed) {
            return;
        }

        killed = true;
        grunt.log.writeln('Stopping LDAP In Memory Server ...');
        exec('kill `cat /tmp/in-memory-ldap.pid`');
        grunt.log.writeln('All done!');
    }

    function killAndExit() {
        kill();
        process.exit();
    }

    // Default task.
    grunt.registerTask('default', ['exec:ldap', 'ldapProcess', 'watch']);
    grunt.registerTask('test', ['exec:ldap', 'ldapProcess', 'phpunit']);
};
