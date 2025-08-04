#!/bin/bash

# Script to push to GitHub after repository creation
echo "🚀 Pushing Alabraj Laravel project to GitHub..."

# Push all commits to main branch
git push -u origin main

echo "✅ Project successfully pushed to:"
echo "https://github.com/AlaaSabryHammad/alabraj-laravel"

echo ""
echo "📊 Repository Statistics:"
git log --oneline | wc -l | xargs echo "Total commits:"
git ls-files | wc -l | xargs echo "Total files:"

echo ""
echo "🎉 Alabraj Construction Management System is now on GitHub!"
