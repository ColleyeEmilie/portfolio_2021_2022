class Portfolio_Controller
{
    constructor()
    {
        this.body = document.body;
    }

    run()
    {
        const ratio = .1;
        const options = {
            root:null,
            rootMargin: '0px',
            threshold: ratio
        }

        const handleIntersect = function(entries, observer){
            entries.forEach(function(entry){
                if(entry.intersectionRatio > ratio){
                    entry.target.classList.add('reveal-visible')
                    observer.unobserve(entry.target);
                }
            })
        }
        const observer =  new IntersectionObserver(handleIntersect, options);
        observer.observe(document.querySelectorAll('[class*="reveal-"]').forEach(function (r){
            observer.observe(r)
        }));
    }
}

window.portfolio = new Portfolio_Controller();
window.addEventListener('load', () => window.portfolio.run());