import React, { useState } from 'react';
import '../styles/styles.css';

function Envelope() {
    const [isOpen, setIsOpen] = useState(false);

    const openLetter = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className={`envelope ${isOpen ? 'open' : ''}`} onClick={openLetter}>
            <div className="letter">
                <p>haan bhai sab theek😎</p>
            </div>
        </div>
    );
}

export default Envelope;
