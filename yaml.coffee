fs = require 'node:fs'
path = require 'node:path'
yaml = require 'yaml'

inputFile = path.join __dirname, 'static', 'manifest.yaml'
outputFile = path.join __dirname, 'public', 'assets', 'site.webmanifest'

fs.writeFileSync outputFile, JSON.stringify yaml.parse fs.readFileSync inputFile, 'utf8'
console.log 'Compiled', inputFile, 'to', outputFile
