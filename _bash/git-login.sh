docker logout registry.gitlab.com
# shellcheck disable=SC2002
cat _bash/pass.txt | docker login registry.gitlab.com --username thanhbd194672 --password-stdin