import { useState } from "react";
import Button from "./Button";

function Modal({ trigger, children, closeText = 'Close', onClose }) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <>
            {/* Trigger */}
            <div onClick={() => setIsOpen(true)}>{trigger}</div>

            {/* Modal */}
            {isOpen && (
                <div className="fixed inset-0 z-50 overflow-y-auto">
                    <div className="flex flex-col items-center justify-center min-h-screen">
                        <div
                            className="fixed inset-0 z-0 bg-black opacity-25"
                            onClick={() => setIsOpen(false)}
                        />


                        <div className="z-50 w-full max-w-2xl p-6 mx-auto overflow-hidden bg-white shadow-xl">
                            <div >{children}</div>


                            <div className="flex justify-end py-4">
                                <Button
                                    onClick={() => {
                                        setIsOpen(false);
                                        onClose && onClose();
                                    }}
                                >
                                    {closeText}
                                </Button>
                            </div>

                        </div>

                    </div>
                </div>
            )}
        </>
    );
}

export default Modal;
