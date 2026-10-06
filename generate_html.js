const fs = require('fs');
const path = require('path');

// Simple markdown to HTML converter
const md = fs.readFileSync('LAPORAN_KP_BOSQ.md', 'utf8');

// Convert basic markdown to HTML
let html = md
  .replace(/```mermaid([\s\S]*?)```/g, '<pre class="mermaid">$1</pre>')
  .replace(/```[\w]*([\s\S]*?)```/g, '<pre><code>$1</code></pre>')
  .replace(/^# (.*$)/gm, '<h1>$1</h1>')
  .replace(/^## (.*$)/gm, '<h2>$1</h2>')
  .replace(/^### (.*$)/gm, '<h3>$1</h3>')
  .replace(/^#### (.*$)/gm, '<h4>$1</h4>')
  .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
  .replace(/\*(.*?)\*/g, '<em>$1</em>')
  .replace(/`(.*?)`/g, '<code>$1</code>')
  .replace(/^---$/gm, '<hr>')
  .replace(/^\| (.*) \|$/gm, (match, row) => {
    const cells = row.split(' | ').map(c => c.trim());
    return '<tr>' + cells.map(c => `<td>${c}</td>`).join('') + '</tr>';
  })
  .replace(/(<tr>.*<\/tr>\n)+/g, '<table>$&</table>')
  .split('\n\n').map(para => {
    if (para.trim().startsWith('<')) return para;
    return '<p>' + para + '</p>';
  }).join('\n');

const fullHtml = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan KP BOS'Q</title>
<style>
  body { font-family: "Times New Roman", Times, serif; font-size: 12pt; margin: 0; padding: 20px 40px; line-height: 1.6; }
  h1 { font-size: 16pt; text-align: center; margin-top: 40px; }
  h2 { font-size: 14pt; }
  h3 { font-size: 13pt; }
  h4 { font-size: 12pt; }
  table { border-collapse: collapse; width: 100%; margin: 15px 0; }
  td, th { border: 1px solid #333; padding: 6px 10px; }
  tr:first-child td { font-weight: bold; background: #f0f0f0; }
  hr { border: 1px solid #333; margin: 30px 0; }
  pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; font-size: 10pt; overflow-x: auto; }
  code { font-family: Consolas, monospace; background: #f5f5f5; padding: 1px 3px; }
  em { font-style: italic; }
  p { text-align: justify; }
</style>
</head>
<body>
${html}
</body>
</html>`;

fs.writeFileSync('LAPORAN_KP_BOSQ.html', fullHtml, 'utf8');
console.log('HTML generated successfully');
