import Modal from "../common/Modal";
import ArticleFilters from "./ArticlesFilters";

const ArticleFilterModal = (props) => {

    const handleChange = (data) => {
        props.onChange && props.onChange(data)
    }

    return (

        <Modal
            trigger={<button className="px-5 py-3 font-bold text-black transition-all border-black border-dotted hover:bg-slate-200">Filter</button>}
            onClose={() => console.log('Modal closed')}
            closeText="Save"
        >

            <div className="flex flex-col w-full h-full space-y-6">
                <h1 className="text-4xl font-bold">Filter</h1>


                <div className="">
                    <ArticleFilters
                        onChange={handleChange}
                        withPostedAt
                    />
                </div>
            </div>

        </Modal>

    );
}

export default ArticleFilterModal;


