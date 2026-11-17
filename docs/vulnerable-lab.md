# Vulnerable Lab Workload

Intentionally outdated workload used to test Sysdig detection and AI-assisted remediation in the OpenShift **lab** (KLAB) cluster. **Never deploy it to Silver/Gold/Emerald or any production environment.**

## What it is

| Item | Value |
| --- | --- |
| Manifest | [`common/openshift.vulnerable-lab.yml`](../common/openshift.vulnerable-lab.yml) |
| Image | `docker.io/nginxinc/nginx-unprivileged:1.16.0` (old tag with publicly known CVEs) |
| Default namespace | `c9ba43-dev` (parameter `NAMESPACE`) |
| Exposure | `ClusterIP` Service only, **no Route** |

The CVEs come from the public upstream image; the repo adds no custom exploit code, credentials or secrets.

## Safety controls

- No Route or Ingress, so nothing is reachable from the internet.
- NetworkPolicy: ingress only from pods in the same namespace, all egress denied.
- Non-root, all capabilities dropped, no privilege escalation, `RuntimeDefault` seccomp, no service-account token, small CPU/memory limits.

## Deploy

```bash
oc login --server=https://api.klab.devops.gov.bc.ca:6443
oc process -f common/openshift.vulnerable-lab.yml -p NAMESPACE=c9ba43-dev | oc apply -f -
oc get pods,svc,route -l app=vuln-lab -n c9ba43-dev   # route list must be empty
```

## Remove

```bash
oc process -f common/openshift.vulnerable-lab.yml -p NAMESPACE=c9ba43-dev | oc delete -f -
```

## Remediation exercise

1. Wait for Sysdig to scan `nginx-unprivileged:1.16.0`.
2. Use the Sysdig investigate/remediate workflow to find the Critical/High CVEs.
3. Remediation should bump the image tag in the manifest to a current, patched version via a PR.

## Notes

- Security scanners (Trivy, Sonar) may flag this manifest or image. That is expected; do not suppress findings globally.
- Do not add a Route, remove the NetworkPolicy, or loosen the security context.
