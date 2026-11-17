# Sample Service Platform

[![Merge](https://github.com/bcgov/AI-Automated-Remediation-in-App-Deployment-in-OpenShift-Testing-/actions/workflows/merge.yml/badge.svg)](https://github.com/bcgov/AI-Automated-Remediation-in-App-Deployment-in-OpenShift-Testing-/actions/workflows/merge.yml)
[![Analysis](https://github.com/bcgov/AI-Automated-Remediation-in-App-Deployment-in-OpenShift-Testing-/actions/workflows/analysis.yml/badge.svg)](https://github.com/bcgov/AI-Automated-Remediation-in-App-Deployment-in-OpenShift-Testing-/actions/workflows/analysis.yml)
[![License](https://img.shields.io/github/license/bcgov/AI-Automated-Remediation-in-App-Deployment-in-OpenShift-Testing-.svg)](/LICENSE)

Sample Service Platform is a web application running on OpenShift. It is also the test bed for evaluating AI-assisted vulnerability remediation (Sysdig detection followed by automated fix pull requests).

## Architecture

| Component | Technology | Path |
| --- | --- | --- |
| Frontend | TypeScript, React, Vite, Caddy with Coraza WAF | [`frontend/`](./frontend) |
| Backend | TypeScript, NestJS, Prisma | [`backend/`](./backend) |
| Database | PostgreSQL, schema managed by Flyway | [`migrations/`](./migrations) |
| Monitoring | Prometheus metrics, alerts | [`monitoring/`](./monitoring) |
| Deployment | OpenShift templates | [`common/`](./common), `backend/openshift.deploy.yml`, `frontend/openshift.deploy.yml` |

## Local development

```bash
docker compose up              # full stack
cd backend  && npm ci && npm run build && npm run lint && npm test
cd frontend && npm ci && npm run build && npm run lint && npm run test:unit
```

End-to-end tests: `cd frontend && npx playwright install --with-deps chromium && npx playwright test --project="chromium"`.

## Delivery pipeline

All deployments run from GitHub Actions (`.github/workflows/`), never from a workstation:

- **Pull request**: build, test, scan and deploy an isolated environment, removed when the PR closes.
- **Merge to `main`**: deploy to TEST, then PROD after validation.
- **Scheduled**: dependency and security scans.
- **Analysis**: SonarCloud and Trivy.

Dependency updates are automated with Renovate.

## Deployment configuration

| Scope | Name | Purpose |
| --- | --- | --- |
| Variable | `OC_SERVER` | OpenShift API server URL |
| Variable (per environment) | `OC_NAMESPACE` | Target namespace (`<plate>-dev`, `-test`, `-prod`) |
| Secret (per environment) | `OC_TOKEN` | Deploy service account token |
| Secret (per environment) | `DB_PASSWORD` | Database password; do not change after first deploy |
| Variable (optional) | `ROUTE_HOST` | Vanity URL; certificates handled by `cert-keeper.yml` |
| Secret (optional) | `SONAR_TOKEN_BACKEND`, `SONAR_TOKEN_FRONTEND` | SonarCloud analysis |
| Secret (optional) | `SYSDIG_API_TOKEN` | Alert sync |

Environment setup helpers are in [`scripts/`](./scripts). They preview changes unless run with `--apply`.

## Security

See [SECURITY.md](./SECURITY.md) for controls and how to report a vulnerability. Do not open public issues for vulnerabilities.

## Vulnerability remediation test workload

[`docs/vulnerable-lab.md`](./docs/vulnerable-lab.md) describes an intentionally outdated, internal-only workload (no Route) used to exercise the remediation workflow in the lab cluster.

## Contributing

See [CONTRIBUTING.md](./CONTRIBUTING.md) and [CODE_OF_CONDUCT.md](./CODE_OF_CONDUCT.md). Licensed under [LICENSE](./LICENSE).
