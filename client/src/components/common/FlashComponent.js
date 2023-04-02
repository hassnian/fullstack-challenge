import { useEffect } from 'react';

const FlashComponent = (props) => {

    const stay = props.stay ? props.stay : 3000

    useEffect(() => {
        const timer = setTimeout(() => props.onExpired(), stay);
        return () => clearTimeout(timer);
    }, []);

    return (
        <div >
            {props.children}
        </div>
    );
}

export default FlashComponent;