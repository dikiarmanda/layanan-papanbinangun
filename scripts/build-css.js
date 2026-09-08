/**
 * Build / watch CSS: app, admin, admin-dashboard.
 * Usage: node scripts/build-css.js [--watch|--minify]
 */
const { spawn } = require("child_process");
const path = require("path");

const root = path.resolve(__dirname, "..");
const mode = process.argv.includes("--minify") ? "minify" : process.argv.includes("--watch") ? "watch" : "once";

const jobs = [
  { name: "app", input: "src/input.css", output: "public/assets/css/app.css" },
  { name: "admin", input: "src/admin.css", output: "public/assets/css/admin.css" },
  { name: "admin-dashboard", input: "src/admin-dashboard.css", output: "public/assets/css/admin-dashboard.css" },
];

const children = [];
let exitCode = 0;

function run(job) {
  const args = ["tailwindcss", "-i", job.input, "-o", job.output];
  if (mode === "watch") args.push("--watch");
  if (mode === "minify") args.push("--minify");

  return new Promise((resolve) => {
    const child = spawn("npx", args, {
      cwd: root,
      stdio: "inherit",
      shell: true,
    });
    children.push(child);
    child.on("exit", (code) => {
      if (code && code !== 0) exitCode = code;
      resolve(code);
    });
  });
}

async function main() {
  if (mode === "watch") {
    await Promise.all(jobs.map(run));
  } else {
    for (const job of jobs) {
      const code = await run(job);
      if (code && code !== 0) process.exit(code);
    }
  }
  process.exit(exitCode);
}

process.on("SIGINT", () => {
  children.forEach((c) => c.kill("SIGINT"));
  process.exit(0);
});

main();
