import { useEffect, useMemo, useState } from "react";
import useAxiosPrivate from "../hooks/use-axios-private";
import { useMutation, useQuery } from "@tanstack/react-query";
import Spinner from "../components/common/Spinner";
import Button from "../components/common/Button";
import FlashComponent from "../components/common/FlashComponent";
import DefaultLayout from "../components/common/DefaultLayout";
import ArticleFilters from "../components/Articles/ArticlesFilters";



const UserPrefrencesForm = (props) => {

    const axios = useAxiosPrivate()

    const defaultPreferences = {
        categories: [],
        authors: [],
        datasources: []
    }

    const [preferences, setPreferences] = useState(defaultPreferences)
    const [preselectedPreferences, setPreselectedPreferences] = useState(defaultPreferences)

    const updateUserPreferences = useMutation((data) => {
        return axios.put('/user/preferences', data)
    }, {
        onSuccess: () => {
            props.onSuccess()
        }
    })

    useEffect(() => {
        if (props.preselected) {
            setPreferences(props.preselected)
            setPreselectedPreferences(props.preselected)
        }
    }, [])

    const handleSave = () => {
        updateUserPreferences.mutate({
            categories: preferences.categories.map((item) => item.id),
            authors: preferences.authors.map((item) => item.id),
            datasources: preferences.datasources.map((item) => item.id)
        })
    }

    const handleFilterChange = (data) => {
        setPreferences(data)
    }

    return (
        <div>

            <ArticleFilters
                preselected={preselectedPreferences}
                onChange={handleFilterChange}
            />

            <div className="flex justify-end mt-10">
                <Button
                    onClick={handleSave}
                    isLoading={updateUserPreferences.isLoading}
                >
                    Save
                </Button>
            </div>

        </div>
    );
}

const UserPrefrences = () => {

    const axios = useAxiosPrivate()
    const [showSuccessMessage, setShowSuccessMessage] = useState(false)

    const getUserPreferences = () => {
        console.log('fetching')
        return axios.get('/user/preferences')
    }

    const { isLoading, data: userPreferences, queryCache, refetch } = useQuery(['userPreferences'], getUserPreferences)

    const preselected = useMemo(() => {
        if (isLoading) { return {} }
        return {
            categories: userPreferences.data?.data?.categories || [],
            authors: userPreferences.data?.data?.authors || [],
            datasources: userPreferences.data?.data?.datasources || []
        };
    }, [userPreferences])

    const handleSuccess = () => {
        setShowSuccessMessage(true)
        queryCache.invalidateQueries('userPreferences')
        refetch()
    }


    if (isLoading) { return <Spinner /> }

    return (
        <DefaultLayout>

            <h1 className="text-3xl font-bold">User Preferences</h1>

            {showSuccessMessage && (<FlashComponent onExpired={() => setShowSuccessMessage(false)}>
                <div className="my-4">
                    <div className="p-4 bg-green-200">User preferences updated successfully</div>
                </div>
            </FlashComponent>)}


            <div className="pt-4">
                <UserPrefrencesForm
                    preselected={preselected}
                    onSuccess={handleSuccess}
                />
            </div>


        </DefaultLayout>
    );
}

export default UserPrefrences;