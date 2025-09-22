#!/bin/bash

echo "Starting PHP style fixes..."

# Function to fix specific issues in PHP files
fix_php_files() {
    find . -name "*.php" -not -path "./vendor/*" -not -path "./node_modules/*" -not -path "./public/*" | while read file; do
        # Skip if file doesn't exist
        if [ ! -f "$file" ]; then
            continue
        fi
        
        echo "Processing $file..."
        
        # Fix != to !== for strict comparison
        sed -i '' 's/!= \?\x27\x27/!== \x27\x27/g' "$file"
        sed -i '' 's/!= \?"/!== "/g' "$file"
        sed -i '' 's/\$[a-zA-Z_][a-zA-Z0-9_]* != \?\x27\x27/\$1 !== \x27\x27/g' "$file"
        
        # Fix == to === for strict comparison  
        sed -i '' 's/== \?\x27\x27/=== \x27\x27/g' "$file"
        sed -i '' 's/== \?"/=== "/g' "$file"
        
        # Fix array() to []
        sed -i '' 's/array(/[/g' "$file"
        
        # Fix explicit string variables - basic pattern
        sed -i '' 's/"%\$\([a-zA-Z_][a-zA-Z0-9_]*\)%"/"%{\$\1}%"/g' "$file"
        sed -i '' 's/"\$\([a-zA-Z_][a-zA-Z0-9_]*\) \$\([a-zA-Z_][a-zA-Z0-9_]*\)"/"{$\1} {$\2}"/g' "$file"
        sed -i '' 's/"\$\([a-zA-Z_][a-zA-Z0-9_]*\) \$\([a-zA-Z_][a-zA-Z0-9_]*\) \$\([a-zA-Z_][a-zA-Z0-9_]*\)"/"{$\1} {$\2} {$\3}"/g' "$file"
        
        # Fix space after cast
        sed -i '' 's/(array)\([a-zA-Z$]\)/(array) \1/g' "$file"
        sed -i '' 's/(string)\([a-zA-Z$]\)/(string) \1/g' "$file"
        sed -i '' 's/(int)\([a-zA-Z$]\)/(int) \1/g' "$file"
        sed -i '' 's/(float)\([a-zA-Z$]\)/(float) \1/g' "$file"
        
        # Fix space after NOT operator
        sed -i '' 's/!\([a-zA-Z$]\)/! \1/g' "$file"
        
        # Fix double quotes to single quotes for simple strings
        sed -i '' "s/\"''/'''/g" "$file"
        
        # Fix trailing comma in single line arrays
        sed -i '' 's/\[\([^][,]*\),\]/[\1]/g' "$file"
        
        # Remove extra spaces before commas
        sed -i '' 's/ ,/,/g' "$file"
        
        # Fix += operator usage
        sed -i '' 's/\([a-zA-Z_$][a-zA-Z0-9_]*\) = \1 +/\1 +=/g' "$file"
        
        # Fix superfluous semicolons
        sed -i '' 's/;;/;/g' "$file"
        
        # Remove trailing whitespace
        sed -i '' 's/[[:space:]]*$//' "$file"
        
        # Fix multiple empty lines to single empty line
        sed -i '' '/^$/N;/^\n$/d' "$file"
        
    done
}

# Function to fix route files specifically
fix_route_files() {
    find routes/ -name "*.php" 2>/dev/null | while read file; do
        if [ ! -f "$file" ]; then
            continue
        fi
        
        echo "Processing route file $file..."
        
        # Fix method argument spacing in routes
        sed -i '' "s/\\('\\)\\([^',]*\\)\\(',\\)\\([^']*\\)\\('\\)/\\1\\2\\3 \\4\\5/g" "$file"
        
        # Convert tabs to spaces
        sed -i '' 's/\t/    /g' "$file"
        
        # Ensure single blank line at end
        sed -i '' -e :a -e '/^\s*$/{$d;N;ba' -e '}' "$file"
        echo >> "$file"
        
    done
}

# Apply fixes
fix_php_files
fix_route_files

echo "PHP style fixes completed!"