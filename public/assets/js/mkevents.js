// RENTAL VALIDATION BUTTON ACTION:

const rentalForm = document.querySelector('#rental_form');
const getButtonForm = document.querySelector('#rental_validation_button');

if (getButtonForm)
{
    getButtonForm.addEventListener('click', () => {
        if (rentalForm.className !== "rental_open")
        {
            rentalForm.className = "col-10 mx-auto border rounded rental_open";
   
        } else {
            rentalForm.className = "col-10 mx-auto border rounded";
        }

        let anchorRentalForm = document.querySelector('#rental_form_anchor');
        anchorRentalForm.getAttribute('#rental_form_section');
        anchorRentalForm.scrollIntoView({ behavior: 'smooth' });
        return anchorRentalForm;
    });
}


// ABOUT SCROLLDOWN

const partnersLink = document.querySelector('#partners_link');

if (partnersLink)
{
    partnersLink.addEventListener('click', () => {
        let partnersAnchor = document.querySelector('#partners_scrolldown');
        partnersAnchor.getAttribute('#toPartners');
        partnersAnchor.scrollIntoView({ behavior: 'smooth' });
        return partnersAnchor;
    });
}


